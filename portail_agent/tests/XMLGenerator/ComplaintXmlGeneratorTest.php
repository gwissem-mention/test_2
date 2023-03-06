<?php

declare(strict_types=1);

namespace App\Tests\XMLGenerator;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\Identity;
use App\Enum\Institution;
use App\Generator\Complaint\ComplaintXmlGenerator;
use App\Referential\Entity\Unit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ComplaintXmlGeneratorTest extends KernelTestCase
{
    private string $xmlContent = '';

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintXmlGenerator $xmlGenerator */
        $xmlGenerator = $container->get(ComplaintXmlGenerator::class);

        $unit = (new Unit(
            null,
            null,
            '103131',
            'CSP VOIRON/SVP/UPS/BRIGADE DE JOUR',
            'CSP VOIRON/SVP/UPS/BRIGADE DE JOUR',
            'Voiron',
            '75',
            '630',
            Institution::PN
        ));

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
                    ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                    ->setBirthday(new \DateTimeImmutable('1967-03-07'))
                    ->setBirthCountry('France')
                    ->setNationality('Française')
                    ->setBirthDepartment('Paris')
                    ->setBirthCity('Paris')
                    ->setBirthPostalCode('75000')
                    ->setBirthInseeCode('75056')
                    ->setBirthDepartmentNumber(75)
                    ->setAddress('15 rue PAIRA, Meudon, 92190')
                    ->setAddressStreetNumber('15')
                    ->setAddressStreetType('rue')
                    ->setAddressStreetName('PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressPostcode('92190')
                    ->setAddressInseeCode('92048')
                    ->setAddressCountry('France')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setMobilePhone('06 12 34 45 57')
                    ->setEmail('jean.dupont@gmail.com')
                    ->setJob('Boulanger')
                    ->setAlertNumber(3)
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
                    ->setLabel('Téléphone mobile')
                    ->setBrand('Apple')
                    ->setModel('iPhone 13')
                    ->setOperator('Orange')
                    ->setSerialNumber(1234567890)
                    ->setPhoneNumber('06 12 34 56 67')
                    ->setAmount(999)
            )
            ->addObject(
                (new MultimediaObject())
                    ->setLabel('Téléphone mobile')
                    ->setBrand('Apple')
                    ->setModel('iPhone 14 Pro')
                    ->setOperator('SFR')
                    ->setSerialNumber(987654321)
                    ->setPhoneNumber('06 21 43 65 87')
                    ->setAmount(1329)
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
            ->setAdditionalInformation(
                (new AdditionalInformation())
                    ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                    ->setCctvAvailable(false)
                    ->setSuspectsKnown(true)
                    ->setSuspectsKnownText('2 hommes')
                    ->setWitnessesPresent(true)
                    ->setWitnessesPresentText('Paul DUPONT')
                    ->setFsiVisit(true)
                    ->setObservationMade(true)
                    ->setVictimOfViolence(false)
                    ->setDescription("Vol d'un Iphone 13")
            );

        /** @var string $xml */
        $xml = $xmlGenerator->generate($complaint, $unit)->asXML();

        $this->xmlContent = $xml;
    }

    public function testFlagSection(): void
    {
        $this->assertStringContainsString('<Flag>', $this->xmlContent);
        $this->assertStringContainsString('<Flag_Test>test</Flag_Test>', $this->xmlContent);
//        $this->assertStringContainsString('<Flag_Debut>03/01/2023 10:02:34</Flag_Debut>', $this->xmlContent);
//        $this->assertStringContainsString('<Flag_Fin>03/01/2023 10:45:04</Flag_Fin>', $this->xmlContent);
//        $this->assertStringContainsString('<Flag_Ip>127.0.0.1</Flag_Ip>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Unite/>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Unite_Departement_Actif/>', $this->xmlContent);
        $this->assertStringContainsString('<Code_Unite>103131</Code_Unite>', $this->xmlContent);
        $this->assertStringContainsString('<unite_dpt>75</unite_dpt>', $this->xmlContent);
        $this->assertStringContainsString('<unite_nom>CSP VOIRON/SVP/UPS/BRIGADE DE JOUR</unite_nom>', $this->xmlContent);
        $this->assertStringContainsString('<unite_adr>Voiron</unite_adr>', $this->xmlContent);
        $this->assertStringContainsString('<unite_tph>630</unite_tph>', $this->xmlContent);
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
        $this->assertStringContainsString('<Personne_Civilite_Sexe>M</Personne_Civilite_Sexe>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nom>DUPONT</Personne_Nom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nom_Marital/>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Prenom>Jean</Personne_Prenom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Date>07/03/1967</Personne_Naissance_Date>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Pays>France</Personne_Naissance_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Departement>75 - Paris</Personne_Naissance_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Codepostal>75000</Personne_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Insee>75056</Personne_Naissance_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Commune>Paris</Personne_Naissance_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_HidNumDep>75</Personne_Naissance_HidNumDep>', $this->xmlContent);
//        $this->assertStringContainsString('<Personne_Situation_Familiale>Marié(e)</Personne_Situation_Familiale>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Nationalite>Française</Personne_Nationalite>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Profession>Boulanger</Personne_Profession>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Pays>France</Personne_Residence_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Departement>92 - Hauts-de-Seine</Personne_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Codepostal>92190</Personne_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Insee>92048</Personne_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Commune>Meudon</Personne_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_HidNumDep>92</Personne_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNo>15</Personne_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueType>rue</Personne_Residence_RueType>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNom>PAIRA</Personne_Residence_RueNom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Adresse>15 rue PAIRA, Meudon, 92190</Personne_Residence_Adresse>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Lieu>Meudon 92190 (France)</Personne_Residence_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Lieu>Paris 75000 (France)</Personne_Naissance_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('</Personne>', $this->xmlContent);
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
//        $this->assertStringContainsString('<Faits_Orientation_Aucune>1</Faits_Orientation_Aucune>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Violences_Description>Je n\'ai pas subit de violences</Faits_Violences_Description>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Orientation>Je n\'ai pas d\'éléments succeptibles de faire avancer l\'enquête.</Faits_Orientation>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Prejudice_Physique>Non</Faits_Prejudice_Physique>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Prejudice_Autre>0</Faits_Prejudice_Autre>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Prejudice_Physique_Description>Je n\'ai pas subit de préjudice physique</Faits_Prejudice_Physique_Description>', $this->xmlContent);
//        $this->assertStringContainsString('<Faits_Prejudice_Autre_Description>Je n\'ai pas subit d\'autre préjudice</Faits_Prejudice_Autre_Description>', $this->xmlContent);
        $this->assertStringContainsString('</Faits>', $this->xmlContent);
    }

    public function testObjectSection(): void
    {
        $this->assertStringContainsString('<Objet>', $this->xmlContent);
//        $this->assertStringContainsString('<Objets_Prejudice_Evaluer>1</Objets_Prejudice_Evaluer>', $this->xmlContent);
        $this->assertStringContainsString('<Objets_Prejudice_Estimation>2328</Objets_Prejudice_Estimation>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Divers>', $this->xmlContent);
        $this->assertStringContainsString('</Objet>', $this->xmlContent);
    }

    public function testObjectDocAdmin(): void
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
        $this->assertStringContainsString('<Objet_Multimedia_Nature>Téléphone mobile</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>1234567890</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description>iPhone 13</Objet_Multimedia_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Tel>06 12 34 56 67</Objet_Multimedia_Nmr_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Operateur>Orange</Objet_Multimedia_Operateur>', $this->xmlContent);
//        $this->assertStringContainsString('<Objet_Multimedia_Opposition>Oui</Objet_Multimedia_Opposition>', $this->xmlContent);
//        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Sim>1234567809</Objet_Multimedia_Nmr_Sim>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Victime>Oui</Objet_Multimedia_Identite_Victime>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Vol_Dans_Vl>Non</Objet_Multimedia_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nature>Téléphone mobile</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>987654321</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description>iPhone 14 Pro</Objet_Multimedia_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Tel>06 21 43 65 87</Objet_Multimedia_Nmr_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Operateur>SFR</Objet_Multimedia_Operateur>', $this->xmlContent);
//        $this->assertStringContainsString('<Objet_Multimedia_Opposition>Oui</Objet_Multimedia_Opposition>', $this->xmlContent);
//        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Sim>1234567809</Objet_Multimedia_Nmr_Sim>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Identite_Victime>Non</Objet_Multimedia_Identite_Victime>', $this->xmlContent);
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

    public function testPaymentMethodObject(): void
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

    public function testContactSection(): void
    {
        $this->assertStringContainsString('<Contact>', $this->xmlContent);
//        $this->assertStringContainsString('<Demandes_Suites_Judiciaires>Oui</Demandes_Suites_Judiciaires>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Declarant>jean.dupont@gmail.com</Mail_Declarant>', $this->xmlContent);
//        $this->assertStringContainsString('<Tel_Domicile_Declarant>01 02 03 04 05</Tel_Domicile_Declarant>', $this->xmlContent);
//        $this->assertStringContainsString('<Tel_Bureau_Declarant>09 01 02 03 04</Tel_Bureau_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Portable_Declarant>06 12 34 45 57</Tel_Portable_Declarant>', $this->xmlContent);
//        $this->assertStringContainsString('<Choix_Rendez_Vous>03/12/2022 00h</Choix_Rendez_Vous>', $this->xmlContent);
//        $this->assertStringContainsString('<Creaneau_Contact>08H-12H</Creaneau_Contact>', $this->xmlContent);
//        $this->assertStringContainsString('<Periode_Contact>Si possible entre 10h et 11h</Periode_Contact>', $this->xmlContent);
        $this->assertStringContainsString('</Contact>', $this->xmlContent);
    }
}