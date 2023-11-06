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
    private ComplaintXmlGenerator $xmlGenerator;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();

        /** @var ComplaintXmlGenerator $xmlGenerator */
        $xmlGenerator = $container->get(ComplaintXmlGenerator::class);
        $this->xmlGenerator = $xmlGenerator;

        $unit = $this->getUnit();
        $complaint = $this->getComplaint();

        /** @var string $xml */
        $xml = $xmlGenerator->generate($complaint, $unit)->asXML();
        $this->xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');

        /** @var Identity $identity */
        $identity = $complaint->getIdentity();
        $complaint
            ->setIdentity(
                $identity->setDeclarantStatus(Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE)
            )
            ->setConsentContactEmail(true)
            ->setConsentContactSMS(true)
            ->setConsentContactPortal(true)
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
                    ->setSameAddressAsDeclarant(true)
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
        $this->assertStringContainsString('<Mail_Unite>ddsp33-csp-arcachon-ppel@interieur.gouv.fr</Mail_Unite>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Unite_Departement_Actif>ddsp33-ppel@interieur.gouv.fr</Mail_Unite_Departement_Actif>', $this->xmlContent);
        $this->assertStringContainsString('<Code_Unite>74181</Code_Unite>', $this->xmlContent);
        $this->assertStringContainsString('<unite_dpt>33</unite_dpt>', $this->xmlContent);
        $this->assertStringContainsString('<unite_nom>Commissariat de police d\'Arcachon</unite_nom>', $this->xmlContent);
        $this->assertStringContainsString('<unite_adr>1 Place de Verdun 33120 ARCACHON</unite_adr>', $this->xmlContent);
        $this->assertStringContainsString('<unite_tph>05 57 72 29 30</unite_tph>', $this->xmlContent);
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
        $this->assertStringContainsString('<Personne_Telephone_Portable>+33676543210</Personne_Telephone_Portable>', $this->xmlContent);
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
        $this->assertStringContainsString('<Personne_Profession>ETUDIANT</Personne_Profession>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Pays>France</Personne_Residence_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Departement>92 - Hauts-de-Seine</Personne_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Codepostal>92190</Personne_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Insee>92048</Personne_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Commune>Meudon</Personne_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_HidNumDep>92</Personne_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNo>15</Personne_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueType/>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_RueNom>Rue PAIRA</Personne_Residence_RueNom>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Rue>15 Rue PAIRA</Personne_Residence_Rue>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Adresse>15 rue PAIRA, Meudon, 92190</Personne_Residence_Adresse>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Residence_Lieu>FRANCE, MEUDON, 15 rue PAIRA, Meudon, 92190</Personne_Residence_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('<Personne_Naissance_Lieu>MEUDON 92190 (France)</Personne_Naissance_Lieu>', $this->xmlContent);
        $this->assertStringContainsString('<France_Connect>Oui</France_Connect>', $this->xmlContent);
        $this->assertStringContainsString('<Profession_INSEE_PEL>Etudiant</Profession_INSEE_PEL>', $this->xmlContent);
        $this->assertStringContainsString('</Personne>', $this->xmlContent);
    }

    public function testPersonLegalRepresentativeSection(): void
    {
        $this->assertStringContainsString('<Representant_Legal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Civilite_Sexe>M</Representant_Legal_Civilite_Sexe>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nom>DUPONT</Representant_Legal_Nom>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nom_Marital/>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Prenom>Jean</Representant_Legal_Prenom>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Portable_Declarant>+33612344557</Tel_Portable_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Date>07/03/1967</Representant_Legal_Naissance_Date>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Pays>France</Representant_Legal_Naissance_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Departement>75 - Paris</Representant_Legal_Naissance_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Codepostal>75000</Representant_Legal_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Insee>75056</Representant_Legal_Naissance_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_Commune>Paris</Representant_Legal_Naissance_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Naissance_HidNumDep>75</Representant_Legal_Naissance_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Nationalite>FRANCAISE</Representant_Legal_Nationalite>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Profession>BOULANGER</Representant_Legal_Profession>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Pays>France</Representant_Legal_Residence_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Departement>92 - Hauts-de-Seine</Representant_Legal_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Codepostal>92190</Representant_Legal_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Insee>92048</Representant_Legal_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_Commune>Meudon</Representant_Legal_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_HidNumDep>92</Representant_Legal_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueNo>15</Representant_Legal_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueType/>', $this->xmlContent);
        $this->assertStringContainsString('<Representant_Legal_Residence_RueNom>Rue PAIRA</Representant_Legal_Residence_RueNom>', $this->xmlContent);
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
        $this->assertStringContainsString('<Personne_Morale_Siret>12345678900000</Personne_Morale_Siret>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Siren>123456789</Personne_Morale_Siren>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_NIC>00000</Personne_Morale_NIC>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Implication>PDG</Personne_Morale_Implication>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Nationalite>FRANCAISE</Personne_Morale_Nationalite>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Secteur/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Juridique/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Pays>France</Personne_Morale_Residence_Pays>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Departement>92 - Hauts-de-Seine</Personne_Morale_Residence_Departement>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Codepostal>92190</Personne_Morale_Residence_Codepostal>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Insee>92048</Personne_Morale_Residence_Insee>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Commune>Meudon</Personne_Morale_Residence_Commune>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_HidNumDep>92</Personne_Morale_Residence_HidNumDep>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueNo>15</Personne_Morale_Residence_RueNo>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Rue>15 Rue PAIRA</Personne_Morale_Residence_Rue>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueType/>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_RueNom>Rue PAIRA</Personne_Morale_Residence_RueNom>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Adresse>15 rue PAIRA, Meudon, 92190</Personne_Morale_Residence_Adresse>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Personne_Morale_Residence_Lieu>FRANCE, MEUDON, 15 rue PAIRA, Meudon, 92190</Personne_Morale_Residence_Lieu>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Mail_Personne_Morale>pdg@netflix.com</Mail_Personne_Morale>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('</Personne_Morale>', $this->xmlContentWithCorporationRepresented);
    }

    public function testFactsSection(): void
    {
        $this->assertStringContainsString('<Faits>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Manop>Je me suis fait voler mon portable.</Faits_Manop>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Adresse>25 Avenue de la République, Bordeaux, 33000</Faits_Localisation_Adresse>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Pays>France</Faits_Localisation_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Departement>33 - Gironde</Faits_Localisation_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Codepostal>33000</Faits_Localisation_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Insee>33063</Faits_Localisation_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Commune>Bordeaux</Faits_Localisation_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_HidNumDep>33</Faits_Localisation_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart>25 Avenue de la République, Bordeaux, 33000</Faits_Adresse_Depart>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_Pays>France</Faits_Adresse_Depart_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_Departement>33 - Gironde</Faits_Adresse_Depart_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_Codepostal>33000</Faits_Adresse_Depart_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_Insee>33063</Faits_Adresse_Depart_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_Commune>Bordeaux</Faits_Adresse_Depart_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Depart_HidNumDep>33</Faits_Adresse_Depart_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee>Place Charles Hernu, Villeurbanne, 69100</Faits_Adresse_Arrivee>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_Pays>France</Faits_Adresse_Arrivee_Pays>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_Departement>69 - Rhône</Faits_Adresse_Arrivee_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_Codepostal>69100</Faits_Adresse_Arrivee_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_Insee>69266</Faits_Adresse_Arrivee_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_Commune>Villeurbanne</Faits_Adresse_Arrivee_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Adresse_Arrivee_HidNumDep>69</Faits_Adresse_Arrivee_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique que les faits on été commis entre 25 Avenue de la République, Bordeaux, 33000 et Place Charles Hernu, Villeurbanne, 69100 dans TRAIN. M. DUPONT Jean nous précise Les faits se sont produits entre le restaurant et l\'appartement d\'un ami. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Localisation_Inconnue/>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Horaire>horaire_inconnu</Faits_Horaire>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Date_Affaire>01/12/2022</Faits_Date_Affaire>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Heure>10</Faits_Heure>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Minute>00</Faits_Minute>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Date_Formate>01/12/2022</Faits_Periode_Affaire_Debut_Date_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Heure_Formate>10</Faits_Periode_Affaire_Debut_Heure_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut_Minute_Formate>00</Faits_Periode_Affaire_Debut_Minute_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Date_Formate>01/12/2022</Faits_Periode_Affaire_Fin_Date_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Heure_Formate>11</Faits_Periode_Affaire_Fin_Heure_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin_Minute_Formate>00</Faits_Periode_Affaire_Fin_Minute_Formate>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Debut>01/12/2022 à 10:00</Faits_Periode_Affaire_Debut>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Periode_Affaire_Fin>01/12/2022 à 11:00</Faits_Periode_Affaire_Fin>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Violences_Aucune/>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Physique>oui</Faits_Prejudice_Physique>', $this->xmlContent);
        //        $this->assertStringContainsString('<Faits_Orientation_Aucune>1</Faits_Orientation_Aucune>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Violences_Description>Je me suis fait taper</Faits_Violences_Description>', $this->xmlContent);
        //        $this->assertStringContainsString('<Faits_Orientation>Je n\'ai pas d\'éléments succeptibles de faire avancer l\'enquête.</Faits_Orientation>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Autre>1</Faits_Prejudice_Autre>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Physique_Description>Dans ce cas vous devrez être examiné par un médecin et présenter un certificat médical indiquant notamment la durée de votre incapacité temporaire de travail. Les précisions relatives à cet examen vous seront communiquées lors de la fixation du rendez-vous pour la signature de votre plainte</Faits_Prejudice_Physique_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Faits_Prejudice_Autre_Description>16698</Faits_Prejudice_Autre_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Date_Exacte_Faits_Connue>Oui</Date_Exacte_Faits_Connue>', $this->xmlContent);
        $this->assertStringContainsString('</Faits>', $this->xmlContent);
    }

    public function testObjectSection(): void
    {
        $this->assertStringContainsString('<Objet>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objets_Prejudice_Evaluer>1</Objets_Prejudice_Evaluer>', $this->xmlContent);
        $this->assertStringContainsString('<Objets_Prejudice_Estimation>16698</Objets_Prejudice_Estimation>', $this->xmlContent);
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
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Victime>Oui</Objet_Doc_Admin_Identite_Victime>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Nom>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Prenom>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Date>01/02/2010</Objet_Doc_Admin_Identite_Naissance_Date>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Departement>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Codepostal>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Commune>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Naissance_Insee>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Doc_Admin_Identite_Residence>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_Departement>92 - Hauts-de-Seine</Objet_Doc_Admin_Identite_Residence_Departement>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_Codepostal>92190</Objet_Doc_Admin_Identite_Residence_Codepostal>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_Commune>Meudon</Objet_Doc_Admin_Identite_Residence_Commune>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_Insee>92048</Objet_Doc_Admin_Identite_Residence_Insee>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_RueNo>15</Objet_Doc_Admin_Identite_Residence_RueNo>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_RueType/>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_RueNom>Rue PAIRA</Objet_Doc_Admin_Identite_Residence_RueNom>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Residence_HidNumDep>92</Objet_Doc_Admin_Identite_Residence_HidNumDep>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Date_Fin_Validite>01/12/2024</Objet_Doc_Admin_Date_Fin_Validite>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Statut>volé</Objet_Doc_Admin_Statut>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Tel>+33612344557</Objet_Doc_Admin_Identite_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Doc_Admin_Identite_Mail>jean.dupont@gmail.com</Objet_Doc_Admin_Identite_Mail>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Doc_Admin_Vol_Dans_Vl>Non</Objet_Doc_Admin_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_Doc_Admin>', $this->xmlContent);
    }

    public function testObjectMultimediaSection(): void
    {
        $this->assertStringContainsString('<Objet_Multimedia>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nature>TELEPHONE PORTABLE</Objet_Multimedia_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie>1324354657</Objet_Multimedia_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_IMEI>BBBB-1234</Objet_Multimedia_IMEI>', $this->xmlContent);
        $this->assertStringNotContainsString('<Objet_Multimedia_Description>Statut : volé - Iphone 13 de couleur grise</Objet_Multimedia_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Tel>+33612345667</Objet_Multimedia_Nmr_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Statut_Tel>volé</Objet_Multimedia_Statut_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Marque_Tel>Apple</Objet_Multimedia_Marque_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Modele_Tel>iPhone 13</Objet_Multimedia_Modele_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Operateur>Orange</Objet_Multimedia_Operateur>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Opposition>Oui</Objet_Multimedia_Opposition>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_Multimedia_Nmr_Sim>1234567809</Objet_Multimedia_Nmr_Sim>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Identite_Victime>Oui</Objet_Multimedia_Identite_Victime>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Multimedia_Vol_Dans_Vl>Non</Objet_Multimedia_Vol_Dans_Vl>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description>Statut : volé - Description console</Objet_Multimedia_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Prejudice_Estimation>999</Objet_Multimedia_Prejudice_Estimation>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Description_Tel>Iphone 13 de couleur grise. Apple iPhone 13</Objet_Multimedia_Description_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_Numeros_Serie_Tel>1234567890</Objet_Multimedia_Numeros_Serie_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Multimedia_IMEI_Tel>ABCD-1234</Objet_Multimedia_IMEI_Tel>', $this->xmlContent);
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
        $this->assertStringContainsString('<Objet_Moyen_Paiement_IBAN>987654321</Objet_Moyen_Paiement_IBAN>', $this->xmlContent);
        // $this->assertStringContainsString('<Objet_Moyen_Paiement_Type_Carte>Mastercard</Objet_Moyen_Paiement_Type_Carte>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Identite_Victime>Oui</Objet_Moyen_Paiement_Identite_Victime>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Statut>Volé</Objet_Moyen_Paiement_Statut>', $this->xmlContent);
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
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Nmr>1234567890</Objet_Moyen_Paiement_Nmr>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Premier_Nmr>AAA</Objet_Moyen_Paiement_Premier_Nmr>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Moyen_Paiement_Dernier_Nmr>XXX</Objet_Moyen_Paiement_Dernier_Nmr>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_Moyen_Paiement>', $this->xmlContent);
    }

    public function testSimpleObjectSection(): void
    {
        $this->assertStringContainsString('<Objet_simple>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Nature>Blouson</Objet_simple_Nature>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Marque>Adidas</Objet_simple_Marque>', $this->xmlContent);
        //        $this->assertStringContainsString('<Objet_simple_Modele>Homme</Objet_simple_Modele>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Numeros_Serie>1234567890</Objet_simple_Numeros_Serie>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Description>Blouson bleu</Objet_simple_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Simple_Statut>Volé</Objet_Simple_Statut>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Simple_Denomination>Blouson</Objet_Simple_Denomination>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Simple_Quantite>1</Objet_Simple_Quantite>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_Simple_prejudice_estimation>100</Objet_Simple_prejudice_estimation>', $this->xmlContent);
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
        $this->assertStringContainsString('<VL_prejudice_estimation>15000</VL_prejudice_estimation>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Pays_Immatriculation>France</VL_Pays_Immatriculation>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Statut>Dégradé</VL_Statut>', $this->xmlContent);
        $this->assertStringNotContainsString('<VL_Genre>', $this->xmlContent);
        $this->assertStringContainsString('</VL>', $this->xmlContent);
    }

    public function testContactSection(): void
    {
        $this->assertStringContainsString('<Contact>', $this->xmlContent);
        $this->assertStringContainsString('<Demande_Suites_Judiciaires>Oui</Demande_Suites_Judiciaires>', $this->xmlContent);
        $this->assertStringContainsString('<Mail_Declarant>jean.dupont@gmail.com</Mail_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Domicile_Declarant>+33123456789</Tel_Domicile_Declarant>', $this->xmlContent);
        //        $this->assertStringContainsString('<Tel_Bureau_Declarant>09 01 02 03 04</Tel_Bureau_Declarant>', $this->xmlContent);
        $this->assertStringContainsString('<Tel_Portable_Declarant>+33612344557</Tel_Portable_Declarant>', $this->xmlContent);
        //        $this->assertStringContainsString('<Choix_Rendez_Vous>03/12/2022 00h</Choix_Rendez_Vous>', $this->xmlContent);
        //        $this->assertStringContainsString('<Creaneau_Contact>08H-12H</Creaneau_Contact>', $this->xmlContent);
        //        $this->assertStringContainsString('<Periode_Contact>Si possible entre 10h et 11h</Periode_Contact>', $this->xmlContent);
        $this->assertStringContainsString('<CONS>Oui</CONS>', $this->xmlContent);
        $this->assertStringContainsString('<CONS_Tel>Oui</CONS_Tel>', $this->xmlContent);
        $this->assertStringContainsString('<CONS_Mail>Oui</CONS_Mail>', $this->xmlContent);
        $this->assertStringContainsString('<CONS_Portalis>Oui</CONS_Portalis>', $this->xmlContent);
        $this->assertStringContainsString('</Contact>', $this->xmlContent);
    }

    public function testVariousSection(): void
    {
        $this->assertStringContainsString('<Divers>', $this->xmlContent);
        $this->assertStringContainsString('<Suspects_Informations>Oui</Suspects_Informations>', $this->xmlContent);
        $this->assertStringContainsString('<Suspects_Description>2 hommes</Suspects_Description>', $this->xmlContent);
        $this->assertStringContainsString('<Temoins_Presents>Oui</Temoins_Presents>', $this->xmlContent);
        $this->assertStringContainsString('<Intervention_Fsi>Oui</Intervention_Fsi>', $this->xmlContent);
        $this->assertStringContainsString('<Constat_Relev_Effectues>Oui</Constat_Relev_Effectues>', $this->xmlContent);
        $this->assertStringContainsString('<Video_Disponible>Oui</Video_Disponible>', $this->xmlContent);
        $this->assertStringContainsString('<Unite_Rdv>Commissariat de police d\'Arcachon</Unite_Rdv>', $this->xmlContent);
        $this->assertStringContainsString('<Rdv_Souhaite>Oui</Rdv_Souhaite>', $this->xmlContent);
        $this->assertStringContainsString('<Enregistrement_Video>Oui</Enregistrement_Video>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<Temoins_Description>Jean Dupont</Temoins_Description>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('<URL_API_PJ>http://localhost/api/complaint/AAAA-BBBB-CCCC/attachments</URL_API_PJ>', $this->xmlContentWithCorporationRepresented);
        $this->assertStringContainsString('</Divers>', $this->xmlContent);
    }

    public function testNonRegisteredVehicleSection(): void
    {
        $this->assertStringContainsString('<Objet_simple>', $this->xmlContent);
        $this->assertStringContainsString('<Objet_simple_Nature>Véhicules non immatriculés</Objet_simple_Nature>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Non_Immat_Statut>Dégradé</VL_Non_Immat_Statut>', $this->xmlContent);
        $this->assertStringContainsString('<VL_Non_Immat_Denomination>Trotinette</VL_Non_Immat_Denomination>', $this->xmlContent);
        $this->assertStringContainsString('</Objet_simple>', $this->xmlContent);
    }

    public function testFaitsLocalisation(): void
    {
        /** @var Complaint $complaint */
        $complaint = $this->getComplaint();
        /** @var Facts $facts */
        $facts = $complaint->getFacts();
        $facts->setPlace('INTERNET')->setAddressAdditionalInformation(null);
        /** @var string $xml */
        $xml = $this->xmlGenerator->generate($complaint, $this->getUnit())->asXML();
        $xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');

        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique que l\'infraction a été commise sur internet sur un site dont il ignore l\'URL. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $xmlContent);

        $facts->setPlace('INTERNET')->setWebsite('www.facebook.com');
        /** @var string $xml */
        $xml = $this->xmlGenerator->generate($complaint, $this->getUnit())->asXML();
        $xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');
        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique que l\'infraction a été commise sur internet sur le site dont l\' URL est www.facebook.com. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $xmlContent);

        $facts->setPlace('RESEAU TELEPHONIQUE');
        /** @var string $xml */
        $xml = $this->xmlGenerator->generate($complaint, $this->getUnit())->asXML();
        $xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');
        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique ignorer le numéro de la ligne téléphonique incriminé. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $xmlContent);

        $facts->setPlace('RESEAU TELEPHONIQUE')->setCallingPhone('+33 0800 000 000');
        /** @var string $xml */
        $xml = $this->xmlGenerator->generate($complaint, $this->getUnit())->asXML();
        $xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');
        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique comme numéro de la ligne téléphonique incriminé +33 0800 000 000. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $xmlContent);

        $facts->setPlace('ECOLE')->setEndAddress(null);
        /** @var string $xml */
        $xml = $this->xmlGenerator->generate($complaint, $this->getUnit())->asXML();
        $xmlContent = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');
        $this->assertStringContainsString('<Faits_Localisation>La personne déclarante indique comme adresse pour le lieu de commission des faits 25 Avenue de la République, Bordeaux, 33000 et comme nature de lieu ECOLE. Sur la présence de violences au moment des faits, la personne déclarante indique : </Faits_Localisation>', $xmlContent);
    }

    private function getUnit(): Unit
    {
        return new Unit(
            'ddsp33-csp-arcachon-ppel@interieur.gouv.fr',
            'ddsp33-ppel@interieur.gouv.fr',
            '74181',
            '74181',
            'Commissariat de police d\'Arcachon',
            '44.65828',
            '-1.1609669',
            '1 Place de Verdun 33120 ARCACHON',
            '33',
            '05 57 72 29 30',
            '24h/24 - 7j/7',
            '4015',
            Institution::PN
        );
    }

    private function getComplaint(): Complaint
    {
        $complaint = (new Complaint())
            ->setFrontId('AAAA-BBBB-CCCC')
            ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
            ->setTest(true)
            ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
            ->setDeclarationNumber('PEL-2022-'.str_pad('1', 8, '0', STR_PAD_LEFT))
            ->setAlert('Alert de test trop longue')
            ->setFranceConnected(true)
            ->setUnitAssigned($this->getUnit()->getCode())
            ->setAppointmentAsked(true)
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
                    ->setAddressStreetName('Rue PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressPostcode('92190')
                    ->setAddressInseeCode('92048')
                    ->setAddressCountry('France')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setMobilePhone('+33 6 12 34 45 57')
                    ->setHomePhone('+33 1 23 45 67 89')
                    ->setEmail('jean.dupont@gmail.com')
                    ->setJob('Boulanger')
                    ->setJobThesaurus('BOULANGER')
                    ->setAlertNumber(3)
            )
            ->setConsentContactEmail(true)
            ->setConsentContactSMS(true)
            ->setConsentContactPortal(true)
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
                    ->setAddressStreetName('Rue PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressInseeCode('92048')
                    ->setAddressPostcode('92190')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setAddressCountry('France')
                    ->setMobilePhone('+33 6 76 54 32 10')
                    ->setEmail('jeremy.dupont@gmail.com')
                    ->setJob('Etudiant')
                    ->setJobThesaurus('ETUDIANT')
            )
            ->setFacts(
                (new Facts())
                    ->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])
                    ->setDescription('Je me suis fait voler mon portable.')
                    ->setExactDateKnown(true)
                    ->setExactPlaceUnknown(true)
                    ->setStartDate(new \DateTimeImmutable('2022-12-01'))
                    ->setEndDate(new \DateTimeImmutable('2022-12-01'))
                    ->setPlace('TRAIN')
                    ->setStartAddress('25 Avenue de la République, Bordeaux, 33000')
                    ->setEndAddress('Place Charles Hernu, Villeurbanne, 69100')
                    ->setStartAddressCountry('France')
                    ->setStartAddressCity('Bordeaux')
                    ->setStartAddressPostalCode('33000')
                    ->setStartAddressInseeCode('33063')
                    ->setStartAddressDepartment('Gironde')
                    ->setStartAddressDepartmentNumber(33)
                    ->setEndAddressCountry('France')
                    ->setEndAddressCity('Villeurbanne')
                    ->setEndAddressPostalCode('69100')
                    ->setEndAddressInseeCode('69266')
                    ->setEndAddressDepartment('Rhône')
                    ->setEndAddressDepartmentNumber(69)
                    ->setExactHourKnown(Facts::EXACT_HOUR_KNOWN_NO)
                    ->setStartHour(new \DateTimeImmutable('09:00'))
                    ->setEndHour(new \DateTimeImmutable('10:00'))
                    ->setAlertNumber(7)
                    ->setAddressAdditionalInformation(
                        "Les faits se sont produits entre le restaurant et l'appartement d'un ami"
                    )
                    ->setVictimOfViolence(true)
                    ->setVictimOfViolenceText('Je me suis fait taper')
            )
            ->addObject(
                (new MultimediaObject())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setNature('TELEPHONE PORTABLE')
                    ->setBrand('Apple')
                    ->setModel('iPhone 13')
                    ->setDescription('Iphone 13 de couleur grise')
                    ->setOperator('Orange')
                    ->setSerialNumber('1234567890')
                    ->setImei('ABCD-1234')
                    ->setPhoneNumber('+33 6 12 34 56 67')
                    ->setAmount(999)
            )
            ->addObject(
                (new MultimediaObject())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setNature('AUTRE NATURE MULTIMEDIA')
                    ->setBrand('Sony')
                    ->setModel('Playstation 4')
                    ->setSerialNumber('1324354657')
                    ->setImei('BBBB-1234')
                    ->setDescription('Description console')
                    ->setAmount(499)
            )
            ->addObject(
                (new AdministrativeDocument())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setType('Permis de conduire')
                    ->setValidityEndDate(new \DateTimeImmutable('2024-12-01'))
                    ->setOwned(true)
            )
            ->addObject(
                (new PaymentMethod())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setType('Carte bancaire')
                    ->setDescription('Carte gold')
                    ->setBank('LCL')
                    ->setBankAccountNumber('987654321')
                    ->setChequeNumber('1234567890')
                    ->setFirstChequeNumber('AAA')
                    ->setLastChequeNumber('XXX')
                    ->setCreditCardNumber('4624 7482 3324 9080')
            )
            ->addObject(
                (new SimpleObject())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setNature('Blouson')
                    ->setDescription('Blouson bleu')
                    ->setSerialNumber('1234567890')
                    ->setQuantity(1)
                    ->setAmount(100)
            )
            ->addObject(
                (new SimpleObject())
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
                    ->setNature('Sac')
                    ->setDescription('Sac bleu')
                    ->setSerialNumber('1234567890')
                    ->setQuantity(1)
                    ->setAmount(100)
            )
            ->addObject(
                (new Vehicle())
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
            ->addObject(
                (new Vehicle())
                    ->setLabel('Trotinette')
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
            )
            ->setAdditionalInformation(
                (new AdditionalInformation())
                    ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                    ->setCctvAvailable(true)
                    ->setSuspectsKnown(true)
                    ->setSuspectsKnownText('2 hommes')
                    ->setWitnessesPresent(true)
                    ->setFsiVisit(true)
                    ->setObservationMade(true)
                    ->addWitness(
                        (new Witness())
                            ->setDescription('Jean Dupont')
                            ->setPhone('+33 6 12 34 45 57')
                            ->setEmail('jean@example.com')
                    )
            );

        return $complaint;
    }
}
