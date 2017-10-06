<?php

namespace AppBundle\CalculationService;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\Entity\Grund;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class GrundCalculator implements EventSubscriber {

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents() {
		return [
			'prePersist',
			'preUpdate',
		];
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist( LifecycleEventArgs $args ) {
		$grund = $args->getObject();

		// only act on "Grund" entity
		if ( ! $grund instanceof Grund ) {
			return;
		}

		$this->calculate( $grund, true );
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function preUpdate( LifecycleEventArgs $args ) {
		$grund     = $args->getObject();
		$changeset = $args->getEntityChangeSet();

		// only act on "Grund" entity
		if ( ! $grund instanceof Grund ) {
			return;
		}

		$this->calculate( $grund, false, $changeset );
		$this->setLedigStatusBulk( $grund, $changeset );
	}

	/**
	 * Calculate and set properties on Grund entity
	 *
	 * @param Grund $grund
	 * @param bool $isNew
	 * @param array $changeset
	 */
	private function calculate( Grund $grund, bool $isNew, array $changeset = [] ) {
		$this->setDatoannonce1( $grund, $isNew, $changeset );
		$this->clearDatoAnnonceFields( $grund, $isNew, $changeset );

		$this->calculateStatus( $grund, $isNew, $changeset );
		$this->calculateSalgstatus( $grund, $isNew, $changeset );
		$this->calculateToDates( $grund );
		$this->calculateBruttoAreal( $grund );
		$this->calculatePris( $grund );
	}

	/**
	 * Remember the first date for 'annonceres'
	 *
	 * @param Grund $grund
	 * @param bool $isNew
	 * @param array $changeset
	 */
	private function setDatoannonce1( Grund $grund, bool $isNew, array $changeset = [] ) {
		$changeKeys = [ 'datoannonce' ];

		if ( $isNew || $this->arrayKeyExist( $changeKeys, $changeset ) ) {
			if ( $grund->getDatoannonce() && ! $grund->getDatoannonce1() ) {
				$grund->setDatoannonce1( $grund->getDatoannonce() );
			}
		}
	}

	/**
	 * Clear 'DatoAnnonce' fields if 'annonceres' is false
	 *
	 * @param Grund $grund
	 */
	private function clearDatoAnnonceFields( Grund $grund, bool $isNew, array $changeset = [] ) {
		$changeKeys = [ 'annonceres' ];

		if ( $isNew || $this->arrayKeyExist( $changeKeys, $changeset ) ) {
			if ( ! $grund->isAnnonceres() ) {
				$grund->setDatoannonce( null );
			}
		}
	}

	/**
	 * Update status when grund set "ledig"
	 * (Should only run on 'bulk' update)
	 *
	 * "Copy-paste" from legacy system (Servlets/Grund.java: setLedigStatus)
	 *
	 * @param Grund $grund
	 * @param array $changeset
	 */
	private function setLedigStatusBulk( Grund $grund, array $changeset = [] ) {
		$changeKeys = [ 'status' ];

		// This should only be true if it's a bulk update
		if ( $this->arrayKeyExist( $changeKeys, $changeset ) && $changeset['status'][1] === GrundStatus::LEDIG ) {

			// Status have allready changed on the entity. We need the old one.
			$status = $changeset['status'][0];
			$type   = $grund->getType();

			if ( $status === GrundStatus::ANNONCERET || ( $status === GrundStatus::FREMTIDIG ) && ! $grund->isAnnonceres() ) {
				$grund->setStatus( GrundStatus::LEDIG );
			} else if ( $status === GrundStatus::AUKTION_SLUT ) {
				if ( $type === GrundType::PARCELHUS ) {
					$grund->setSalgstype( SalgsType::FASTPRIS );

					$this->clearStatusFields( $grund );
					$this->clearAuktionsDateFields( $grund );
				} else if ( $type === GrundType::STORPARCEL ) {
					$grund->setSalgstype( SalgsType::ETGM2 );

					$this->clearStatusFields( $grund );
					$this->clearAuktionsDateFields( $grund );
				} else if ( $type === GrundType::ERHVERV ) {
					$grund->setSalgstype( SalgsType::KVADRATMETERPRIS );

					$this->clearStatusFields( $grund );
					$this->clearAuktionsDateFields( $grund );
				} else if ( $type === GrundType::ANDRE ) {
					$grund->setSalgstype( SalgsType::FASTPRIS );

					$this->clearStatusFields( $grund );
					$this->clearAuktionsDateFields( $grund );
				}
			}
		}
	}

	/**
	 * "Copy-paste" from legacy system (Servlets/Grund.java: clearDateFields)
	 *
	 * @param Grund $grund
	 */
	private function clearStatusFields( Grund $grund ) {
		$grund->setStatus( GrundStatus::LEDIG );
		$grund->setSalgstatus( GrundSalgStatus::LEDIG );
	}

	/**
	 * "Copy-paste" from legacy system (Servlets/Grund.java: clearDateFields)
	 *
	 * @param Grund $grund
	 */
	private function clearAuktionsDateFields( Grund $grund ) {
		$grund->setAuktionstartdato( null );
		$grund->setAuktionslutdato( null );
	}

	/**
	 * Update status base on dates for auktion, reserveret, etc.
	 *
	 * "Copy-paste" from legacy system (A - WorkflowMixin.js: setStatusField)
	 *
	 * @param Grund $grund
	 * @param bool $isNew
	 * @param array $changeset
	 */
	private function calculateStatus( Grund $grund, bool $isNew, array $changeset = [] ) {

		$changeKeys = [ 'annonceres', 'salgstype', 'auktionstartdato', 'auktionslutdato', 'datoannonce' ];

		if ( $isNew || $this->arrayKeyExist( $changeKeys, $changeset ) ) {

			$today = new \DateTime();
			$today->setTime( 12, 0 );

			if ( $grund->getSalgstype() === SalgsType::AUKTION ) {
				// Auktion slut dato i fortid: Auktion slut
				if ( $grund->getAuktionslutdato() && $grund->getAuktionslutdato() < $today ) {
					$grund->setStatus( GrundStatus::AUKTION_SLUT );
				} // Ny ell. 'Fremtidig'/'Annonceret' grund - annonce dato i fortiden: Annonceret
				else if ( ( $isNew || $grund->getStatus() === GrundStatus::FREMTIDIG || $grund->getStatus() === GrundStatus::ANNONCERET ) && $grund->getDatoannonce() && $grund->getDatoannonce() <= $today ) {
					$grund->setStatus( GrundStatus::ANNONCERET );
				} // Auktion slut dato i fremtid: Fremtidig
				else if ( $grund->getAuktionslutdato() && $grund->getAuktionslutdato() > $today ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				} // Ingen auktion start dato: Fremtidig
				else if ( ! $grund->getAuktionstartdato() ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				} // Ny - ingen annonce dato: Fremtidig
				else if ( $isNew && ! $grund->getDatoannonce() ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				} // Ny ell. 'Annonceret' grund - annoncedato i fremtiden: Fremtidig
				else if ( ( $isNew || $grund->getStatus() === GrundStatus::ANNONCERET ) && $grund->getDatoannonce() && $grund->getDatoannonce() > $today ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				}

				// Salgstype anden end Auktion
			} else {

				// Ny ell. 'Fremtidig'/'Annonceret' grund - annonce dato i fortiden: Annonceret
				if ( ( $isNew || $grund->getStatus() === GrundStatus::FREMTIDIG || $grund->getStatus() === GrundStatus::ANNONCERET ) && $grund->getDatoannonce() && $grund->getDatoannonce() <= $today ) {
					$grund->setStatus( GrundStatus::ANNONCERET );
				} // Ny ell. 'Skal-annonceres' grund - ingen annonce dato endnu: Fremtidig
				else if ( ( $isNew || $grund->isAnnonceres() ) && ! $grund->getDatoannonce() ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				} // Ny ell. 'Annonceret' grund - annoncedato i fremtiden: Fremtidig
				else if ( ( $isNew || $grund->getStatus() === GrundStatus::ANNONCERET ) && $grund->getDatoannonce() && $grund->getDatoannonce() > $today ) {
					$grund->setStatus( GrundStatus::FREMTIDIG );
				}
			}
		}
	}

	/**
	 * Update salgstatus base on dates for auktion, reserveret, etc.
	 *
	 * "Copy-paste" from legacy system (A - WorkflowMixin.js: setSalgstatusField)
	 *
	 * @param Grund $grund
	 * @param bool $isNew
	 * @param array $changeset
	 */
	private function calculateSalgstatus( Grund $grund, bool $isNew, array $changeset = [] ) {
		$changeKeys = [ 'annonceres', 'salgstype', 'auktionstartdato', 'auktionslutdato', 'datoannonce', 'resstart', 'resslut',
			'beloebanvist', 'skoederekv', 'accept', 'auktionstartdato', 'auktionslutdato', 'tilbudstart' ];

		if ( $isNew || $this->arrayKeyExist( $changeKeys, $changeset ) ) {

			$today = new \DateTime();
			$today->setTime( 12, 0 );

			if ( $grund->getBeloebanvist() ) {
				$grund->setSalgstatus( GrundSalgStatus::SOLGT );
			} else if ( $grund->getSkoederekv() ) {
				$grund->setSalgstatus( GrundSalgStatus::SKOEDE_REKVIRERET );
			} else if ( $grund->getAccept() ) {
				$grund->setSalgstatus( GrundSalgStatus::ACCEPTERET );
			} else if ( $grund->getAuktionstartdato() && $grund->getAuktionslutdato() && $grund->getAuktionslutdato() < $today ) {
				$grund->setSalgstatus( GrundSalgStatus::AUKTION_SLUT );
			} else if ( $grund->getTilbudstart() ) {
				$grund->setSalgstatus( GrundSalgStatus::TILBUD_SENDT );
			} else if ( $grund->getResstart() ) {
				$grund->setSalgstatus( GrundSalgStatus::RESERVERET );
			} else {
				$grund->setSalgstatus( GrundSalgStatus::LEDIG );
			}
		}
	}

	/**
	 * If the are not set, update 'til og med' dates base on their respective 'fra' dates
	 *
	 * "Copy-paste" from legacy system
	 *
	 * @param \AppBundle\Entity\Grund $grund
	 */
	private function calculateToDates( Grund $grund ) {

		// Default reservation is 14 days
		if ( $grund->getResstart() && ! $grund->getResslut() ) {
			$endDay = clone $grund->getResstart();
			$endDay->add( new \DateInterval( 'P14D' ) );

			$grund->setResslut( $endDay );
		}

		// Default 'tilbud' is 28 days
		if ( $grund->getTilbudstart() && ! $grund->getTilbudslut() ) {
			$endDay = clone $grund->getTilbudstart();
			$endDay->add( new \DateInterval( 'P28D' ) );

			$grund->setTilbudslut( $endDay );
		}

	}

	/**
	 * Calulate Bruttoareal
	 *
	 * @param Grund $grund
	 */
	private function calculateBruttoAreal( Grund $grund ) {
		$grund->setBruttoareal( $grund->getAreal() - $grund->getArealvej() - $grund->getArealkotelet() );
	}

	/**
	 * Calculate price
     *
     * "Copy-paste" from legacy system (GrundForm.js: calculatePris)
	 *
	 * @param Grund $grund
	 */
	private function calculatePris( Grund $grund ) {
		if ( $grund->getSalgstype() == SalgsType::KVADRATMETERPRIS || $grund->getSalgstype() == SalgsType::ETGM2 ) {
			$grund->setPriskoor1( $grund->getAntalkorr1() * $grund->getAkrkorr1() );
			$grund->setPriskoor2( $grund->getAntalkorr2() * $grund->getAkrkorr2() );
			$grund->setPriskoor3( $grund->getAntalkorr3() * $grund->getAkrkorr3() );

			$this->calculatePrisExKorr( $grund );

            $pris = $grund->getPrisfoerkorrektion() + $grund->getPriskoor1() + $grund->getPriskoor2() + $grund->getPriskoor3();
            $grund->setPris( $pris );
		} else if ( $grund->getSalgstype() == SalgsType::FASTPRIS ) {
            $grund->setPris($grund->getFastpris());
		} else if ( $grund->getSalgstype() == SalgsType::AUKTION ) {
		    $grund->setPris($grund->getAntagetbud());
        }

        $date = \DateTime::createFromFormat('Y-m-d', '2011-01-01');

        if($grund->getAccept()) {
            if($grund->getAccept() < $date) {
                $grund->setSalgsprisumoms(0);
            } else {
                $grund->setSalgsprisumoms($grund->getPris() * 0.8);
            }
        } else {
            $grund->setSalgsprisumoms(0);
        }
	}

	private function calculatePrisExKorr( Grund $grund ) {
	    $prism2 = $grund->getPrism2() ? $grund->getPrism2() : 0;
	    $maxetm2 = $grund->getMaxetagem2() ? $grund->getMaxetagem2() : 0;
	    $bareal = $grund->getBruttoareal() ? $grund->getBruttoareal() : 0;

	    if($grund->getType() === GrundType::STORPARCEL) {
	        $grund->setPrisfoerkorrektion($prism2 * $maxetm2);
        } else {
	        $grund->setPrisfoerkorrektion($prism2 * $bareal);
        }
    }

	/**
	 * Check if at least one value in $needles exists as a key in $haystack
	 *
	 * @param array $needles
	 * @param array $haystack
	 *
	 * @return bool
	 */
	private function arrayKeyExist( array $needles, array $haystack ) {
		foreach ( $needles as $needle ) {
			if ( array_key_exists( $needle, $haystack ) ) {
				return true;
			}
		}

		return false;
	}

}