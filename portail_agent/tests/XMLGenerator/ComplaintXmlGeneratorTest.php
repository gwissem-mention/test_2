<?php

declare(strict_types=1);

namespace App\Tests\XMLGenerator;

use App\AppEnum\Institution;
use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Corporation;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\Identity;
use App\Entity\Witness;
use App\Generator\Complaint\ComplaintXmlGenerator;
use App\Referential\Entity\Unit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ComplaintXmlGeneratorTest extends KernelTestCase
{
    private string $xmlContent = '';
    private string $xmlContentWithCorporationRepresented = '';

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintXmlGenerator $xmlGenerator */
        $xmlGenerator = $container->get(ComplaintXmlGenerator::class);

        $unit = new Unit(
            'ddsp38-csp-voiron-ppel@interieur.gouv.fr',
            'ddsp38-ppel@interieur.gouv.fr',
            '103131',
            '103131',
            'Commissariat de police de Voiron',
            '45.362186',
            '5.59077',
            '114 cours Becquart Castelbon 38500 VOIRON',
            '38',
            '04 76 65 93 93',
            '24h/24 - 7j/7',
            '4083',
            Institution::PN
        );

        $complaint = (new Complaint())
            ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
            ->setTest(true)
            ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
            ->setDeclarationNumber('PEL-2022-'.str_pad('1', 8, '0', STR_PAD_LEFT))
            ->setOptinNotification(true)
            ->setAlert('Alert de test trop longue')
            ->setIdentity(
                (new Identity())
                    ->setFirstname('Jean')
                    ->setLastname('DUPONT')
                    ->setCivility(Identity::CIVILITY_MALE)
                    ->setDeclarantStatus(Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE)
                    ->setBirthday(new \DateTimeImmutable('1967-03-07'))
                    ->setBirthCountry('France')
                    ->setNationality('FRANCAISE')
                    ->setBirthDepartment('Paris')
                    ->setBirthCity('Paris')
                    ->setBirthPostalCode('75000')
                    ->setBirthInseeCode('75056')
                    ->setBirthDepartmentNumber(75)
                    ->setAddress('15 rue PAIRA, Meudon, 92190')
                    ->setAddressStreetNumber('15')
                    ->setAddressStreetType('Rue')
                    ->setAddressStreetName('PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressPostcode('92190')
                    ->setAddressInseeCode('92048')
                    ->setAddressCountry('France')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setMobilePhone('06 12 34 45 57')
                    ->setHomePhone('01 23 45 67 89')
                    ->setEmail('jean.dupont@gmail.com')
                    ->setJob('Boulanger')
                    ->setAlertNumber(3)
            )
            ->setConsentContactElectronics(true)
            ->setpersonLegalRepresented(
                (new Identity())
                    ->setFirstname('Jeremy')
                    ->setLastname('DUPONT')
                    ->setCivility(Identity::CIVILITY_MALE)
                    ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                    ->setBirthday(new \DateTimeImmutable('2000-02-14'))
                    ->setBirthCountry('France')
                    ->setNationality('FRANCAISE')
                    ->setBirthDepartment('Hauts-de-Seine')
                    ->setBirthDepartmentNumber(92)
                    ->setBirthCity('Meudon')
                    ->setBirthPostalCode('92190')
                    ->setBirthInseeCode('92048')
                    ->setAddress('15 rue PAIRA, Meudon, 92190')
                    ->setAddressStreetNumber('15')
                    ->setAddressStreetType('Rue')
                    ->setAddressStreetName('PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressInseeCode('92048')
                    ->setAddressPostcode('92190')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setAddressCountry('France')
                    ->setMobilePhone('06 76 54 32 10')
                    ->setEmail('jeremy.dupont@gmail.com')
                    ->setJob('Etudiant')
            )
            ->setFacts(
                (new Facts())
                    ->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])
                    ->setDescription('Je me suis fait voler mon portable.')
                    ->setExactDateKnown(true)
                    ->setExactPlaceUnknown(true)
                    ->setStartDate(new \DateTimeImmutable('2022-12-01'))
                    ->setEndDate(new \DateTimeImmutable('2022-12-01'))
                    ->setPlace('Restaurant')
                    ->setStartAddress('25 Avenue Georges Pompidou, Lyon, 69003')
                    ->setEndAddress('Place Charles Hernu, Villeurbanne, 69100')
                    ->setCountry('France')
                    ->setCity('Lyon')
                    ->setPostalCode('69003')
                    ->setInseeCode('69123')
                    ->setDepartment('Rhône')
                    ->setDepartmentNumber(69)
                    ->setExactHourKnown(Facts::EXACT_HOUR_KNOWN_NO)
                    ->setStartHour(new \DateTimeImmutable('09:00'))
                    ->setEndHour(new \DateTimeImmutable('10:00'))
                    ->setAlertNumber(7)
                    ->setAddressAdditionalInformation(
                        "Les faits se sont produits entre le restaurant et l'appartement d'un ami"
                    )
            )
            ->addObject(
                (new MultimediaObject())
                    ->setLabel('Téléphone')
                    ->setNature('Téléphone portable')
                    ->setBrand('Apple')
                    ->setModel('iPhone 13')
                    ->setDescription('Iphone 13 de couleur grise')
                    ->setOperator('Orange')
                    ->setSerialNumber('1234567890')
                    ->setPhoneNumber('06 12 34 56 67')
                    ->setAmount(999)
            )
            ->addObject(
                (new MultimediaObject())
                    ->setLabel('Smartphone')
                    ->setNature('Téléphone portable')
                    ->setBrand('Apple')
                    ->setModel('iPhone 14 Pro')
                    ->setOperator('SFR')
                    ->setSerialNumber('987654321')
                    ->setPhoneNumber('06 21 43 65 87')
                    ->setAmount(1329)
            )
            ->addObject(
                (new MultimediaObject())
                    ->setLabel('Console')
                    ->setNature('Multimédia')
                    ->setBrand('Sony')
                    ->setModel('Playstation 4')
                    ->setSerialNumber('1324354657')
                    ->setDescription('Description console')
                    ->setAmount(499)
            )
            ->addObject(
                (new AdministrativeDocument())
                    ->setType('Permis de conduire')
            )
            ->addObject(
                (new PaymentMethod())
                    ->setType('Carte bancaire')
                    ->setDescription('Carte gold')
                    ->setBank('LCL')
            )
            ->addObject(
                (new SimpleObject())
                    ->setNature('Blouson')
                    ->setDescription('Blouson bleu')
                    ->setAmount(100)
            )
            ->addObject(
                (new Vehicle())
                    ->setLabel('Voiture')
                    ->setBrand('Citroën')
                    ->setModel('C3')
                    ->setRegistrationNumber('AA-123-AA')
                    ->setRegistrationCountry('France')
                    ->setInsuranceCompany('AXA')
                    ->setInsuranceNumber('1458R147R')
                    ->setNature('CAMION')
                    ->setDegradationDescription('Rétroviseur cassé')
                    ->setAmount(15000)
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
            )
            ->setAdditionalInformation(
                (new AdditionalInformation())
                    ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                    ->setCctvAvailable(false)
                    ->setSuspectsKnown(true)
                    ->setSuspectsKnownText('2 hommes')
                    ->setWitnessesPresent(true)
                    ->setFsiVisit(true)
                    ->setObservationMade(true)
                    ->addWitness(
                        (new Witness())
                            ->setDescription('Jean Dupont')
                            ->setPhone('06 12 34 45 57')
                            ->setEmail('jean@example.com')
                    )
            );

        /** @var string $xml */
        $xml = $xmlGenerator->generate($complaint, $unit)->asXML();

        $this->xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');

        /** @var Identity $identity */
        $identity = $complaint->getIdentity();
        $complaint
            ->setIdentity(
                $identity->setDeclarantStatus(Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE)
            )
            ->setConsentContactElectronics(true)
            ->setPersonLegalRepresented(null)
            ->setCorporationRepresented(
                (new Corporation())
                    ->setSiretNumber('12345678900000')
                    ->setCompanyName('Netflix')
                    ->setDeclarantPosition('PDG')
                    ->setNationality('FRANCAISE')
                    ->setContactEmail('pdg@netflix.com')
                    ->setPhone('0612345678')
                    ->setCountry('France')
                    ->setDepartment('Paris')
                    ->setDepartmentNumber(75)
                    ->setCity('Paris')
                    ->setPostCode('75000')
                    ->setInseeCode('75056')
                    ->setStreetNumber(1)
                    ->setStreetName('Rue de la république')
                    ->setStreetType('Rue')
                    ->setAddress('1 Rue de la république, Paris, 75000')
            );

        /** @var string $xmlWithCorporationRepresented */
        $xmlWithCorporationRepresented = $xmlGenerator->generate($complaint, $unit)->asXML();

        $this->xmlContentWithCorporationRepresented = mb_convert_encoding($xmlWithCorporationRepresented, 'UTF-8', 'ISO-8859-1');
    }

    public function testFlagSection(): void
    {
        $this->assertStringContainsString('<Flag>', $this->xmlContent);
        $this->assertStringContainsString('<Flag_Test>test</Flag_Test>', $this->xmlContent);
        //        $this->assertStringContainsString('<Flag_Debut>03/01/2023 10:02:34</Flag_Debut>', $this->xmlContent);
        //        $this->assertStringContainsString('<Flag_Fin>03/01/2023 10:45:04</Flag_Fin>', $this->xmlContent);
        //        $this->assertStringContainsString('<Flag_Ip>127.0.0.1</Flag_Ip>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Unite>ddsp38-csp-voiron-ppel@interieur.gouv.fr</Mail_Unite>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Unite_Departement_Actif>ddsp38-ppel@interieur.gouv.fr</Mail_Unite_Departement_Actif>', $this->xmlContent);
        $this->assertStringContainsString('<Code_Unite>103131</Code_Unite>', $this->xmlContent);
        $this->assertStringContainsString('<unite_dpt>38</unite_dpt>', $this->xmlContent);
        $this->assertStringContainsString('<unite_nom>Commissariat de police de Voiron</unite_nom>', $this->xmlContent);
        $this->assertStringContainsString('<unite_adr>114 cours Becquart Castelbon 38500 VOIRON</unite_adr>', $this->xmlContent);
        $this->assertStringContainsString('<unite_tph>04 76 65 93 93</unite_tph>', $this->xmlContent);
        $this->assertStringContainsString('<unite_institution>PN</unite_institution>', $this->xmlContent);
        //        $this->assertStringContainsString('<TC_Domicile>9</TC_Domicile>', $this->xmlContent);
        //        $this->assertStringContainsString('<TC_Domicile>9</TC_Domicile>', $this->xmlContent);
        //        $this->assertStringContainsString('<Code_Unite_TC_Faits>2556</Code_Unite_TC_Faits>', $this->xmlContent);
        $this->assertStringContainsString('</Flag>', $this->xmlContent);
    }

    public function testPersonSection(): void
    {
        $this->assertStringContainsString('<Personne>', $this->xmlContent);
        $this->assertStringNotContainsString('<Personne_Lien>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Implication>victime</Personne_Implication>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Telephone_Portable>06 76 54 32 10</Personne_Telephone_Portable>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Civilite_Sexe>M</Personne_Civilite_Sexe>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nom>DUPONT</Personne_Nom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nom_Marital/>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Prenom>Jeremy</Personne_Prenom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Date>14/02/2000</Personne_Naissance_Date>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Pays>France</Personne_Naissance_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Departement>92 - Hauts-de-Seine</Personne_Naissance_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Codepostal>92190</Personne_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Insee>92048</Personne_Naissance_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Commune>Meudon</Personne_Naissance_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_HidNumDep>92</Personne_Naissance_HidNumDep>', $this->xmlContent);
        //        $this->assertStringContainsString('<Personne_Situation_Familiale>Marié(e)</Personne_Situation_Familiale>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nationalite>FRANCAISE</Personne_Nationalite>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Profession>Etudiant</Personne_Profession>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Pays>France</Personne_Residence_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Departement>92 - Hauts-de-Seine</Personne_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Codepostal>92190</Personne_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Insee>92048</Personne_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Commune>Meudon</Personne_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_HidNumDep>92</Personne_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNo>15</Personne_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueType>Rue</Personne_Residence_RueType>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNom>PAIRA</Personne_Residence_RueNom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Adresse>15 rue PAIRA, Meudon, 92190</Personne_Residence_Adresse>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Lieu>FRANCE, MEUDON, 15 rue PAIRA, Meudon, 92190</Personne_Residence_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Lieu>MEUDON 92190 (France)</Personne_Naissance_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('</Personne>', $this->xmlContent);
    }

    public function testPersonLegalRepresentativeSection(): void
    {
        $this->assertStringContainsString('<Representant_Legal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Civilite_Sexe>M</Representant_Legal_Civilite_Sexe>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nom>DUPONT</Representant_Legal_Nom>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nom_Marital/>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Prenom>Jean</Representant_Legal_Prenom>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Portable_Declarant>06 12 34 45 57</Tel_Portable_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Date>07/03/1967</Representant_Legal_Naissance_Date>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Pays>France</Representant_Legal_Naissance_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Departement>75 - Paris</Representant_Legal_Naissance_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Codepostal>75000</Representant_Legal_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Insee>75056</Representant_Legal_Naissance_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Commune>Paris</Representant_Legal_Naissance_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_HidNumDep>75</Representant_Legal_Naissance_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nationalite>FRANCAISE</Representant_Legal_Nationalite>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Profession>Boulanger</Representant_Legal_Profession>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Pays>France</Representant_Legal_Residence_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Departement>92 - Hauts-de-Seine</Representant_Legal_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Codepostal>92190</Representant_Legal_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Insee>92048</Representant_Legal_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Commune>Meudon</Representant_Legal_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_HidNumDep>92</Representant_Legal_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueNo>15</Representant_Legal_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueType>Rue</Representant_Legal_Residence_RueType>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueNom>PAIRA</Representant_Legal_Residence_RueNom>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Adresse>15 rue PAIRA, Meudon, 92190</Representant_Legal_Residence_Adresse>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Lieu>FRANCE, MEUDON, 15 rue PAIRA, Meudon, 92190</Representant_Legal_Residence_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Lieu>PARIS 75000 (France)</Representant_Legal_Naissance_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('</Representant_Legal>', $this->xmlContent);
    }

    public function testCorporationRepresentedSection(): void
    {
        $this->assertStringContainsString('<Personne_Morale>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Raison>Netflix</Personne_Morale_Raison>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Num_Registre_Commerce/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Siren>123456789</Personne_Morale_Siren>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_NIC>00000</Personne_Morale_NIC>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Implication>PDG</Personne_Morale_Implication>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Nationalite>FRANCAISE</Personne_Morale_Nationalite>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Secteur/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Juridique/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Pays>France</Personne_Morale_Residence_Pays>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Departement>75 - Paris</Personne_Morale_Residence_Departement>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Codepostal>75000</Personne_Morale_Residence_Codepostal>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Insee>75056</Personne_Morale_Residence_Insee>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Commune>Paris</Personne_Morale_Residence_Commune>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_HidNumDep>75</Personne_Morale_Residence_HidNumDep>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueNo>1</Personne_Morale_Residence_RueNo>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueType>Rue</Personne_Morale_Residence_RueType>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueNom>Rue de la république</Personne_Morale_Residence_RueNom>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Adresse>1 Rue de la république, Paris, 75000</Personne_Morale_Residence_Adresse>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Lieu>PARIS 75000 (France)</Personne_Morale_Residence_Lieu>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('</Personne_Morale>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringNotContainsString('<Personne_Moral_Siret>', $this->xmlContentWithCorporationRepresented);
    }

    public function testFactsSection(): void
    {
        $this->assertStringContainsString('<Faits>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Expose>Vol / Dégradation</Faits_Expose>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Manop>Je me suis fait voler mon portable.</Faits_Manop>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Pays>France</Faits_Localisation_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Departement>69 - Rhône</Faits_Localisation_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Codepostal>69003</Faits_Localisation_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Insee>69123</Faits_Localisation_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Commune>Lyon</Faits_Localisation_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_HidNumDep>69</Faits_Localisation_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation>Restaurant</Faits_Localisation>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Inconnue>1</Faits_Localisation_Inconnue>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Horaire>horaire_inconnu</Faits_Horaire>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Date_Affaire>01/12/2022</Faits_Date_Affaire>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Heure>09</Faits_Heure>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Minute>00</Faits_Minute>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Date_Formate>01/12/2022</Faits_Periode_Affaire_Debut_Date_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Heure_Formate>09</Faits_Periode_Affaire_Debut_Heure_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Minute_Formate>00</Faits_Periode_Affaire_Debut_Minute_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Date_Formate>01/12/2022</Faits_Periode_Affaire_Fin_Date_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Heure_Formate>10</Faits_Periode_Affaire_Fin_Heure_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Minute_Formate>00</Faits_Periode_Affaire_Fin_Minute_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut>01/12/2022 à 09:00:00</Faits_Periode_Affaire_Debut>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin>01/12/2022 à 10:00:00</Faits_Periode_Affaire_Fin>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Violences_Aucune>1</Faits_Violences_Aucune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Physique>non</Faits_Prejudice_Physique>', $this->xmlContent);
        //        $this->assertStringContainsString('<Faits_Orientation_Aucune>1</Faits_Orientation_Aucune>', $this->xmlContent);
        //        $this->assertStringContainsString('<Faits_Violences_Description>Je n\'ai pas subit de violences</Faits_Violences_Description>', $this->xmlContent);
        //        $this->assertStringContainsString('<Faits_Orientation>Je n\'ai pas d\'éléments succeptibles de faire avancer l\'enquête.</Faits_Orientation>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Autre/>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Physique_Description/>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Autre_Description/>', $this->xmlContent);
        $this->assertStringContainsString('</Faits>', $this->xmlContent);
    }

    public function testObjectSection(): void
    {
        $this->assertStringContainsString('<Objet>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objets_Prejudice_Evaluer>1</Objets_Prejudice_Evaluer>', $this->xmlContent);
        $this->assertStringContainsString('<Objets_Prejudice_Estimation>17927</Objets_Prejudice_Estimation>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Divers>', $this->xmlContent);
        $this->assertStringContainsString('</Objet>', $this->xmlContent);
    }

    public function testObjectDocAdminSection(): void
    {
        $this->assertStringContainsString('<Objet_Doc_Admin>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Doc_Admin_Pays_Delivrance>France</Objet_Doc_Admin_Pays_Delivrance>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Type>Permis de conduire</Objet_Doc_Admin_Type>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Doc_Admin_Numero>1234567890</Objet_Doc_Admin_Numero>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Doc_Admin_Date_Delivrance>20/06/2022</Objet_Doc_Admin_Date_Delivrance>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Doc_Admin_Autorite>Préfecture de Paris</Objet_Doc_Admin_Autorite>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Doc_Admin_Description>Permis de conduire récent</Objet_Doc_Admin_Description>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Victime>Oui</Objet_Doc_Admin_Identite_Victime>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Nom>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Prenom>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Date>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Departement>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Commune>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Insee>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_Departement>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_Commune>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_Insee>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_RueNo>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_RueType>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_RueNom>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_HidNumDep>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence_HidNumDep>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Doc_Admin_Vol_Dans_Vl>Non</Objet_Doc_Admin_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_Doc_Admin>', $this->xmlContent);
    }

    public function testObjectMultimediaSection(): void
    {
        $this->assertStringContainsString('<Objet_Multimedia>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nature>Téléphone portable</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>1234567890</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description>Iphone 13 de couleur grise</Objet_Multimedia_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Tel>06 12 34 56 67</Objet_Multimedia_Nmr_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Operateur>Orange</Objet_Multimedia_Operateur>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Opposition>Oui</Objet_Multimedia_Opposition>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Sim>1234567809</Objet_Multimedia_Nmr_Sim>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Identite_Victime>Oui</Objet_Multimedia_Identite_Victime>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Vol_Dans_Vl>Non</Objet_Multimedia_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nature>Téléphone portable</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>987654321</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description/>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Tel>06 21 43 65 87</Objet_Multimedia_Nmr_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Operateur>SFR</Objet_Multimedia_Operateur>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nature>Multimédia</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>1324354657</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description>Description console</Objet_Multimedia_Description>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Opposition>Oui</Objet_Multimedia_Opposition>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Sim>1234567809</Objet_Multimedia_Nmr_Sim>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Nom>DURAND</Objet_Multimedia_Identite_Nom>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Prenom>Charles</Objet_Multimedia_Identite_Prenom>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_Date>17/04/1965</Objet_Multimedia_Identite_Naissance_Date>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance>France</Objet_Multimedia_Identite_Naissance>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_Departement>37 - Indre-et-Loire</Objet_Multimedia_Identite_Naissance_Departement>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_Codepostal>37000</Objet_Multimedia_Identite_Naissance_Codepostal>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_Commune>Tours</Objet_Multimedia_Identite_Naissance_Commune>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_Insee>37261</Objet_Multimedia_Identite_Naissance_Insee>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence>France</Objet_Multimedia_Identite_Residence>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_Departement>37 - Indre-et-Loire</Objet_Multimedia_Identite_Residence_Departement>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_Codepostal>37000</Objet_Multimedia_Identite_Residence_Codepostal>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_Commune>Tours</Objet_Multimedia_Identite_Residence_Commune>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_Insee>37261</Objet_Multimedia_Identite_Residence_Insee>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_RueNo>102</Objet_Multimedia_Identite_Residence_RueNo>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_RueType>avenue</Objet_Multimedia_Identite_Residence_RueType>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_RueNom>Grammont</Objet_Multimedia_Identite_Residence_RueNom>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Naissance_HidNumDep>37</Objet_Multimedia_Identite_Naissance_HidNumDep>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Residence_HidNumDep>37</Objet_Multimedia_Identite_Residence_HidNumDep>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Vol_Dans_Vl>Non</Objet_Multimedia_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_Multimedia>', $this->xmlContent);
    }

    public function testPaymentMethodObjectSection(): void
    {
        $this->assertStringContainsString('<Objet_Moyen_Paiement>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Type>Carte bancaire</Objet_Moyen_Paiement_Type>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Devise>EURO</Objet_Moyen_Paiement_Devise>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Description>Carte gold</Objet_Moyen_Paiement_Description>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Opposition>Non</Objet_Moyen_Paiement_Opposition>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Banque>LCL</Objet_Moyen_Paiement_Banque>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Type_Carte>Mastercard</Objet_Moyen_Paiement_Type_Carte>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Identite_Victime>Oui</Objet_Moyen_Paiement_Identite_Victime>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Vol_Dans_Vl>Non</Objet_Moyen_Paiement_Vol_Dans_Vl>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Nom>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Prenom>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_Date>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_Departement>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_Codepostal>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_Commune>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_Insee>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_Departement>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_Codepostal>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_Commune>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_Insee>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_RueNo>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_RueType>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_RueNom>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Naissance_HidNumDep>', $this->xmlContent);
        // $this->assertStringNotContainsString('<Objet_Moyen_Paiement_Identite_Residence_HidNumDep>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Vol_Dans_Vl>Non</Objet_Moyen_Paiement_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_Moyen_Paiement>', $this->xmlContent);
    }

    public function testSimpleObjectSection(): void
    {
        $this->assertStringContainsString('<Objet_simple>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Nature>Blouson</Objet_simple_Nature>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Marque>Adidas</Objet_simple_Marque>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Modele>Homme</Objet_simple_Modele>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Numeros_Serie>123456</Objet_simple_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Description>Blouson bleu</Objet_simple_Description>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Identite_Victime>Oui</Objet_simple_Identite_Victime>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Vol_Dans_Vl>Non</Objet_simple_Vol_Dans_Vl>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Nom>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Prenom>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_Date>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_Departement>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_Codepostal>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_Commune>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_Insee>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_Departement>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_Codepostal>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_Commune>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_Insee>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_RueNo>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_RueType>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_RueNom>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Naissance_HidNumDep>', $this->xmlContent);
        //        $this->assertStringNotContainsString('<Objet_simple_Identite_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_simple>', $this->xmlContent);
    }

    public function testVehicleSection(): void
    {
        $this->assertStringContainsString('<VL>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Nmr_Immatriculation>AA-123-AA</VL_Nmr_Immatriculation>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Type_Commercial>C3</VL_Type_Commercial>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Marque>Citroën</VL_Marque>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Assurance_Nom>AXA</VL_Assurance_Nom>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Assurance_Police>1458R147R</VL_Assurance_Police>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Degradation>Oui</VL_Degradation>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Nature>CAMION</VL_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Degradation_Liste>Rétroviseur cassé</VL_Degradation_Liste>', $this->xmlContent);
        $this->assertStringContainsString('</VL>', $this->xmlContent);
    }

    public function testContactSection(): void
    {
        $this->assertStringContainsString('<Contact>', $this->xmlContent);
        $this->assertStringContainsString('<Demandes_Suites_Judiciaires>Oui</Demandes_Suites_Judiciaires>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Declarant>jean.dupont@gmail.com</Mail_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Domicile_Declarant>01 23 45 67 89</Tel_Domicile_Declarant>', $this->xmlContent);
        //        $this->assertStringContainsString('<Tel_Bureau_Declarant>09 01 02 03 04</Tel_Bureau_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Portable_Declarant>06 12 34 45 57</Tel_Portable_Declarant>', $this->xmlContent);
        //        $this->assertStringContainsString('<Choix_Rendez_Vous>03/12/2022 00h</Choix_Rendez_Vous>', $this->xmlContent);
        //        $this->assertStringContainsString('<Creaneau_Contact>08H-12H</Creaneau_Contact>', $this->xmlContent);
        //        $this->assertStringContainsString('<Periode_Contact>Si possible entre 10h et 11h</Periode_Contact>', $this->xmlContent);
        $this->assertStringContainsString('<CONS>Oui</CONS>', $this->xmlContent);
        $this->assertStringContainsString('</Contact>', $this->xmlContent);
    }
}
