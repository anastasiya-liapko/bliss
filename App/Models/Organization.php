<?php

namespace App\Models;

use App\FileUploader;
use App\Morpher;
use App\SiteInfo;
use App\UniqueRule;
use Core\Model;
use DateTime;
use PDO;
use PhpOffice\PhpWord\TemplateProcessor;
use Rakit\Validation\Validator;

/**
 * Class Organization.
 *
 * @package App\Models
 */
class Organization extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The ID.
     *
     * @var int|null
     */
    private $id;

    /**
     * The type.
     *
     * @var string|null
     */
    private $type;

    /**
     * The vat.
     *
     * @var float|null
     */
    private $vat;

    /**
     * The legal_name.
     *
     * @var string|null
     */
    private $legal_name;

    /**
     * The tin.
     *
     * @var string|null
     */
    private $tin;

    /**
     * The cio.
     *
     * @var string|null
     */
    private $cio;

    /**
     * The bin.
     *
     * @var string|null
     */
    private $bin;

    /**
     * Is the licensed activity.
     *
     * @var int
     */
    private $is_licensed_activity = 0;

    /**
     * The license type.
     *
     * @var string|null
     */
    private $license_type;

    /**
     * The license number.
     *
     * @var string|null
     */
    private $license_number;

    /**
     * The category_id.
     *
     * @var int|null
     */
    private $category_id;

    /**
     * The legal address.
     *
     * @var string|null
     */
    private $legal_address;

    /**
     * The registration address.
     *
     * @var string|null
     */
    private $registration_address;

    /**
     * The fact address.
     *
     * @var string|null
     */
    private $fact_address;

    /**
     * The bik.
     *
     * @var string|null
     */
    private $bik;

    /**
     * The bank name.
     *
     * @var string|null
     */
    private $bank_name;

    /**
     * The correspondent account.
     *
     * @var string|null
     */
    private $correspondent_account;

    /**
     * The settlement account.
     *
     * @var string|null
     */
    private $settlement_account;

    /**
     * The boss full name.
     *
     * @var string|null
     */
    private $boss_full_name;

    /**
     * The boss position.
     *
     * @var string|null
     */
    private $boss_position;

    /**
     * The boss basis acts.
     *
     * @var string|null
     */
    private $boss_basis_acts;

    /**
     * The boss basis acts number.
     *
     * @var string|null
     */
    private $boss_basis_acts_number;

    /**
     * The boss basis acts issued date.
     *
     * @var string|null
     */
    private $boss_basis_acts_issued_date;

    /**
     * The boss passport number.
     *
     * @var string|null
     */
    private $boss_passport_number;

    /**
     * The boss passport issued date.
     *
     * @var string|null
     */
    private $boss_passport_issued_date;

    /**
     * The boss passport division code.
     *
     * @var string|null
     */
    private $boss_passport_division_code;

    /**
     * The boss passport issued by.
     *
     * @var string|null
     */
    private $boss_passport_issued_by;

    /**
     * The boss birth date.
     *
     * @var string|null
     */
    private $boss_birth_date;

    /**
     * The boss birth place.
     *
     * @var string|null
     */
    private $boss_birth_place;

    /**
     * The email.
     *
     * @var string|null
     */
    private $email;

    /**
     * The phone.
     *
     * @var string|null
     */
    private $phone;

    /**
     * Is the documents checked.
     *
     * @var int
     */
    private $is_documents_checked = 0;

    /**
     * The document snils.
     *
     * @var array|null
     */
    private $document_snils;

    /**
     * The document passport.
     *
     * @var array|null
     */
    private $document_passport;

    /**
     * The document statute with tax mark.
     *
     * @var array|null
     */
    private $document_statute_with_tax_mark;

    /**
     * The document participants decision.
     *
     * @var array|null
     */
    private $document_participants_decision;

    /**
     * The document bin.
     *
     * @var array|null
     */
    private $document_bin;

    /**
     * The document order on appointment.
     *
     * @var array|null
     */
    private $document_order_on_appointment;

    /**
     * The document statute of current edition.
     *
     * @var array|null
     */
    private $document_statute_of_current_edition;

    /**
     * The document statute of current edition.
     *
     * @var array|null
     */
    private $document_contract;

    /**
     * The document statute of current edition.
     *
     * @var array|null
     */
    private $document_questionnaire_fl_115;

    /**
     * The document statute of current edition.
     *
     * @var array|null
     */
    private $document_joining_application;

    /**
     * The morpher handler.
     *
     * @var Morpher
     */
    private $morpher_handler;

    /**
     * The organization constructor.
     *
     * @param array $data (optional) Initial property values.
     * @param array $documents (optional) Documents.
     * @param Morpher|null $morpher_handler (optional) Morpher.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct(
        array $data = [],
        array $documents = [],
        Morpher $morpher_handler = null
    ) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        foreach ($documents as $key => $value) {
            $this->$key = $value;
        }

        $this->morpher_handler = $morpher_handler ?? new Morpher();
    }

    /**
     * Finds an organization model by the id.
     *
     * @param int $id The id.
     *
     * @return mixed The organization object if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM organizations WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Deletes by id.
     *
     * @param int $id The id.
     *
     * @return bool True if success, false otherwise.
     */
    public static function deleteById(int $id): bool
    {
        $sql = 'DELETE FROM organizations WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets organization categories.
     *
     * @return array Array of results.
     */
    public static function getOrganizationCategories(): array
    {
        $sql = 'SELECT * FROM organization_categories ORDER BY id DESC';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Gets organization category name.
     *
     * @param int $id The category id.
     *
     * @return mixed The category name if find, false otherwise.
     */
    public static function getOrganizationCategoryName(int $id)
    {
        $sql = 'SELECT name FROM organization_categories WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Gets the transliterated document name.
     *
     * @param string $name The document name.
     * @return string The transliterated document name.
     */
    public static function getTransliteratedDocumentName(string $name): string
    {
        switch ($name) {
            case 'document_snils':
                $transliterated_name = 'snils';
                break;
            case 'document_passport':
                $transliterated_name = 'pasport';
                break;
            case 'document_statute_with_tax_mark':
                $transliterated_name = 'ustav_s_otmetkoj_nalogovogo_organa';
                break;
            case 'document_participants_decision':
                $transliterated_name = 'protokol_obshchego_sobraniya_uchastnikov';
                break;
            case 'document_bin':
                $transliterated_name = 'ogrn';
                break;
            case 'document_order_on_appointment':
                $transliterated_name = 'prikaz_o_naznachenii_edinolichnogo_ispolnitelnogo_organa';
                break;
            case 'document_statute_of_current_edition':
                $transliterated_name = 'ustav_dejstvuyushchej_redakcii';
                break;
            case 'document_contract':
                $transliterated_name = 'soglashenie';
                break;
            case 'document_questionnaire_fl_115':
                $transliterated_name = 'anketa_fz_115';
                break;
            case 'document_joining_application':
                $transliterated_name = 'zayavlenie_o_prisoedinenii';
                break;
            default:
                $transliterated_name = '';
                break;
        }

        return $transliterated_name;
    }

    /**
     * Gets the russian document name.
     *
     * @param string $name The document name.
     * @return string The russian document name.
     */
    public static function getRussianDocumentName(string $name): string
    {
        switch ($name) {
            case 'document_snils':
                $transliterated_name = 'СНИЛС';
                break;
            case 'document_passport':
                $transliterated_name = 'Копия паспорта';
                break;
            case 'document_statute_with_tax_mark':
                $transliterated_name = 'Устав с отметкой налогового органа';
                break;
            case 'document_participants_decision':
                $transliterated_name = 'Протокол общего собрания участников';
                break;
            case 'document_bin':
                $transliterated_name = 'ОГРН';
                break;
            case 'document_order_on_appointment':
                $transliterated_name = 'Приказ о назначении единоличного исполнительного органа';
                break;
            case 'document_statute_of_current_edition':
                $transliterated_name = 'Устав действующей редакции';
                break;
            case 'document_contract':
                $transliterated_name = 'Соглашение';
                break;
            case 'document_questionnaire_fl_115':
                $transliterated_name = 'Анкета-опросник по 115 ФЗ';
                break;
            case 'document_joining_application':
                $transliterated_name = 'Заявление о присоединении';
                break;
            default:
                $transliterated_name = '';
                break;
        }

        return $transliterated_name;
    }

    /**
     * Gets the template relative url.
     *
     * @param string $template_name The template name.
     *
     * @return string The template relative url.
     */
    public static function getTemplateUrl(string $template_name): string
    {
        $document_root = SiteInfo::getDocumentRoot();

        $templates_url        = '/documents/templates';
        $edited_templates_url = '/documents/edited-templates';

        $edited_templates_path = "{$document_root}/public/documents/edited-templates";
        $edited_template_path  = "{$edited_templates_path}/{$template_name}.docx";

        $template_url = file_exists($edited_template_path)
            ? "{$edited_templates_url}/{$template_name}.docx" : "{$templates_url}/{$template_name}.docx";

        return $template_url;
    }

    /**
     * Creates the account for a organization.
     *
     * @return bool True if success, false otherwise.
     * @throws \Rakit\Validation\RuleQuashException
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->validateOnCreate();

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->validatePassportIssuedDate($this->getBossPassportIssuedDate());
        $this->validateAge($this->getBossBirthDate());

        if (! empty($this->getErrors())) {
            return false;
        }

        $this->uploadFiles(
            $this->getDocuments(),
            SiteInfo::getDocumentRoot() . '/documents/organizations/tin-' . $this->getTin()
        );

        $this->createDocuments();

        $db = static::getDB();

        $this->boss_basis_acts_issued_date = date('Y-m-d', strtotime($this->getBossBasisActsIssuedDate()));
        $this->boss_passport_issued_date   = date('Y-m-d', strtotime($this->getBossPassportIssuedDate()));
        $this->boss_birth_date             = date('Y-m-d', strtotime($this->getBossBirthDate()));

        $stmt = $db->prepare('INSERT INTO organizations (type, vat, legal_name, tin, cio, bin,
                           is_licensed_activity, license_type, license_number, category_id, legal_address, 
                           registration_address, fact_address, bik, bank_name, correspondent_account, 
                           settlement_account, boss_full_name, boss_position, boss_basis_acts, boss_basis_acts_number,
                           boss_basis_acts_issued_date, boss_passport_number, boss_passport_issued_date,
                           boss_passport_division_code, boss_passport_issued_by, boss_birth_date, boss_birth_place,
                           phone, email) 
                           VALUES (:type, :vat, :legal_name, :tin, :cio, :bin, :is_licensed_activity, :license_type,
                                   :license_number, :category_id, :legal_address, :registration_address, :fact_address,
                                   :bik, :bank_name, :correspondent_account, :settlement_account, :boss_full_name,
                                   :boss_position, :boss_basis_acts, :boss_basis_acts_number,
                                   :boss_basis_acts_issued_date, :boss_passport_number, :boss_passport_issued_date,
                                   :boss_passport_division_code, :boss_passport_issued_by, :boss_birth_date,
                                   :boss_birth_place, :phone, :email)');

        $stmt->bindValue(
            ':type',
            $this->getType(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':vat',
            $this->getVat(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':legal_name',
            $this->getLegalName(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':tin',
            $this->getTin(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':cio',
            $this->getCio(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':bin',
            $this->getBin(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':is_licensed_activity',
            $this->getIsLicensedActivity(),
            PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':license_type',
            $this->getLicenseType(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':license_number',
            $this->getLicenseNumber(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':category_id',
            $this->getCategoryId(),
            PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':legal_address',
            $this->getLegalAddress(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':registration_address',
            $this->getRegistrationAddress(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':fact_address',
            $this->getFactAddress(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':bik',
            $this->getBik(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':bank_name',
            $this->getBankName(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':correspondent_account',
            $this->getCorrespondentAccount(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':settlement_account',
            $this->getSettlementAccount(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_full_name',
            $this->getBossFullName(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_position',
            $this->getBossPosition(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_basis_acts',
            $this->getBossBasisActs(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_basis_acts_number',
            $this->getBossBasisActsNumber(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_basis_acts_issued_date',
            $this->getBossBasisActsIssuedDate(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_passport_number',
            $this->getBossPassportNumber(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_passport_issued_date',
            $this->getBossPassportIssuedDate(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_passport_division_code',
            $this->getBossPassportDivisionCode(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_passport_issued_by',
            $this->getBossPassportIssuedBy(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_birth_date',
            $this->getBossBirthDate(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':boss_birth_place',
            $this->getBossBirthPlace(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':phone',
            $this->getPhone(),
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':email',
            $this->getEmail(),
            PDO::PARAM_STR
        );

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось сохранить данные об организации, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Gets the organization name.
     *
     * @return string
     */
    public function getOrganizationName(): string
    {
        if ($this->getType() === 'llc') {
            $legal_name         = str_replace(['ООО', 'ооо', '"', '«', '»'], '', $this->getLegalName());
            $legal_name_trimmed = trim($legal_name);
            $legal_name_ucfirst = ucfirst($legal_name_trimmed);

            return 'Общество с ограниченной ответственностью «' . $legal_name_ucfirst . '»';
        }

        return 'ИП «' . $this->getBossFullName() . '»';
    }

    /**
     * Gets the boss name and initials.
     *
     * @return string
     */
    public function getBossNameAndInitials(): string
    {
        $result = '';

        $array = explode(' ', trim($this->getBossFullName()));

        $result .= isset($array[0]) ? $array[0] : '';
        $result .= isset($array[1]) && ! empty($array[1]) ? ' ' . mb_substr($array[1], 0, 1, 'UTF-8') . '.' : '';
        $result .= isset($array[2]) && ! empty($array[2]) ? mb_substr($array[2], 0, 1, 'UTF-8') . '.' : '';

        return $result;
    }

    /**
     * Maybe gets the boss full name in genitive case.
     *
     * @return string
     * @throws \Exception
     */
    public function maybeGetBossFullNameInGenitiveCase(): string
    {
        $boss_full_name = $this->getBossFullName();

        /* @var object|bool $inclined_boss_full_name */
        $inclined_boss_full_name = $this->morpher_handler->getInclinedWord($boss_full_name, true);

        if ($inclined_boss_full_name) {
            return $inclined_boss_full_name->Р;
        }

        return $boss_full_name;
    }

    /**
     * Gets the basis acts name.
     *
     * @return string
     */
    public function getBossBasisActsName(): string
    {
        switch ($this->getBossBasisActs()) {
            case 'statute':
                $name = 'Устава';
                break;
            case 'proxy':
                $name = 'Доверенности';
                break;
            case 'trust_management_agreement':
                $name = 'Договора доверительного управления';
                break;
            case 'certificate':
                $name = 'Свидетельства';
                break;
            default:
                $name = '';
                break;
        }

        return $name;
    }

    /**
     * Gets the basis acts name.
     *
     * @return string
     */
    public function getBossBasisActsFullInfo(): string
    {
        $result = '';

        $boss_basis_acts_name = $this->getBossBasisActsName();

        if (empty($boss_basis_acts_name)) {
            return $result;
        }

        $result .= $boss_basis_acts_name;

        $boss_basis_acts_number = $this->getBossBasisActsNumber();

        if (! empty($boss_basis_acts_number)) {
            $result .= ' ' . $boss_basis_acts_number;
        }

        $boss_basis_acts_issued_date = $this->getBossBasisActsIssuedDate();

        if (! empty($boss_basis_acts_issued_date)) {
            $result .= ' от ' . $boss_basis_acts_issued_date;
        }

        return $result;
    }

    /**
     * Saves signed documents.
     *
     * @param array $document_contract
     * @param array $document_questionnaire_fl_115
     * @param array $document_joining_application
     *
     * @return bool
     */
    public function saveSignedDocuments(
        array $document_contract,
        array $document_questionnaire_fl_115,
        array $document_joining_application
    ): bool {
        $this->document_contract             = $document_contract;
        $this->document_questionnaire_fl_115 = $document_questionnaire_fl_115;
        $this->document_joining_application  = $document_joining_application;

        return $this->uploadFiles(
            $this->getSignedDocuments(),
            SiteInfo::getDocumentRoot() . '/documents/organizations/tin-' . $this->getTin()
        );
    }

    /**
     * Uploads files.
     *
     * @codeCoverageIgnore
     *
     * @param array $files
     * @param string $upload_dir
     *
     * @return bool
     */
    public function uploadFiles(array $files, string $upload_dir): bool
    {
        foreach ($files as $key => $file) {
            $transliterated_file_name = static::getTransliteratedDocumentName($key);

            $file_uploader = new FileUploader("{$upload_dir}/{$transliterated_file_name}/");
            $file_uploader->upload($file, $transliterated_file_name);
        }

        return true;
    }

    /**
     * Creates documents.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function createDocuments(): bool
    {
        $upload_dir = SiteInfo::getDocumentRoot() . '/documents/unsigned/organizations/tin-' . $this->getTin() . '/';

        if (! is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $this->createJoiningApplicationDocument($upload_dir);
        $this->createContractDocument($upload_dir);
        $this->createQuestionnaireFl115Document($upload_dir);

        return true;
    }

    /**
     * Gets errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Gets the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Gets the vat.
     *
     * @return float|null
     */
    public function getVat(): ?float
    {
        return $this->vat;
    }

    /**
     * Gets the legal name.
     *
     * @return string|null
     */
    public function getLegalName(): ?string
    {
        return $this->legal_name;
    }

    /**
     * Gets the tin.
     *
     * @return string|null
     */
    public function getTin(): ?string
    {
        return $this->tin;
    }

    /**
     * Gets the cio.
     *
     * @return string|null
     */
    public function getCio(): ?string
    {
        return $this->cio;
    }

    /**
     * Gets the bin.
     *
     * @return string|null
     */
    public function getBin(): ?string
    {
        return $this->bin;
    }

    /**
     * Gets the licensed activity.
     *
     * @return int
     */
    public function getIsLicensedActivity(): int
    {
        return $this->is_licensed_activity;
    }

    /**
     * Gets the license type.
     *
     * @return string|null
     */
    public function getLicenseType(): ?string
    {
        return $this->license_type;
    }

    /**
     * Gets the license number.
     *
     * @return string|null
     */
    public function getLicenseNumber(): ?string
    {
        return $this->license_number;
    }

    /**
     * Gets the category_id.
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    /**
     * Gets the legal address.
     *
     * @return string|null
     */
    public function getLegalAddress(): ?string
    {
        return $this->legal_address;
    }

    /**
     * Gets the registration address.
     *
     * @return string|null
     */
    public function getRegistrationAddress(): ?string
    {
        return $this->registration_address;
    }

    /**
     * Gets the fact address.
     *
     * @return string|null
     */
    public function getFactAddress(): ?string
    {
        return $this->fact_address;
    }

    /**
     * Gets the bik.
     *
     * @return string|null
     */
    public function getBik(): ?string
    {
        return $this->bik;
    }

    /**
     * Gets the bank name.
     *
     * @return string|null
     */
    public function getBankName(): ?string
    {
        return $this->bank_name;
    }

    /**
     * Gets the correspondent account.
     *
     * @return string|null
     */
    public function getCorrespondentAccount(): ?string
    {
        return $this->correspondent_account;
    }

    /**
     * Gets the settlement account.
     *
     * @return string|null
     */
    public function getSettlementAccount(): ?string
    {
        return $this->settlement_account;
    }

    /**
     * Gets the boss full name.
     *
     * @return string|null
     */
    public function getBossFullName(): ?string
    {
        return $this->boss_full_name;
    }

    /**
     * Gets the boss position.
     *
     * @return string|null
     */
    public function getBossPosition(): ?string
    {
        return $this->boss_position;
    }

    /**
     * Gets the boss basis acts.
     *
     * @return string|null
     */
    public function getBossBasisActs(): ?string
    {
        return $this->boss_basis_acts;
    }

    /**
     * Gets the boss acts number.
     *
     * @return string|null
     */
    public function getBossBasisActsNumber(): ?string
    {
        return $this->boss_basis_acts_number;
    }

    /**
     * Gets the boss acts issued date.
     *
     * @return string|null
     */
    public function getBossBasisActsIssuedDate(): ?string
    {
        return $this->boss_basis_acts_issued_date;
    }

    /**
     * Gets the boss passport number.
     *
     * @return string|null
     */
    public function getBossPassportNumber(): ?string
    {
        return $this->boss_passport_number;
    }

    /**
     * Gets the boss passport issued date.
     *
     * @return string|null
     */
    public function getBossPassportIssuedDate(): ?string
    {
        return $this->boss_passport_issued_date;
    }

    /**
     * Gets the boss passport division code.
     *
     * @return string|null
     */
    public function getBossPassportDivisionCode(): ?string
    {
        return $this->boss_passport_division_code;
    }

    /**
     * Gets the boss passport issued by
     *
     * @return string|null
     */
    public function getBossPassportIssuedBy(): ?string
    {
        return $this->boss_passport_issued_by;
    }

    /**
     * Gets the boss birth date.
     *
     * @return string|null
     */
    public function getBossBirthDate(): ?string
    {
        return $this->boss_birth_date;
    }

    /**
     * Gets the birth place.
     *
     * @return string|null
     */
    public function getBossBirthPlace(): ?string
    {
        return $this->boss_birth_place;
    }

    /**
     * Gets the email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Gets the phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Gets the document snils url.
     *
     * @return array|null
     */
    public function getDocumentSnils(): ?array
    {
        return $this->document_snils;
    }

    /**
     * Gets the document passport.
     *
     * @return array|null
     */
    public function getDocumentPassport(): ?array
    {
        return $this->document_passport;
    }

    /**
     * Gets the document statute with tax mark.
     *
     * @return array|null
     */
    public function getDocumentStatuteWithTaxMark(): ?array
    {
        return $this->document_statute_with_tax_mark;
    }

    /**
     * Gets the document participants decision.
     *
     * @return array|null
     */
    public function getDocumentParticipantsDecision(): ?array
    {
        return $this->document_participants_decision;
    }

    /**
     * Gets the document bin.
     *
     * @return array|null
     */
    public function getDocumentBin(): ?array
    {
        return $this->document_bin;
    }

    /**
     * Gets the document order on appointment.
     *
     * @return array|null
     */
    public function getDocumentOrderOnAppointment(): ?array
    {
        return $this->document_order_on_appointment;
    }

    /**
     * Gets the document statute of current edition.
     *
     * @return array|null
     */
    public function getDocumentStatuteOfCurrentEdition(): ?array
    {
        return $this->document_statute_of_current_edition;
    }

    /**
     * Gets the document contract.
     *
     * @return array|null
     */
    public function getDocumentContract(): ?array
    {
        return $this->document_contract;
    }

    /**
     * Gets the document questionnaire FL 115.
     *
     * @return array|null
     */
    public function getDocumentQuestionnaireFl115(): ?array
    {
        return $this->document_questionnaire_fl_115;
    }

    /**
     * Gets the document joining application.
     *
     * @return array|null
     */
    public function getDocumentJoiningApplication(): ?array
    {
        return $this->document_joining_application;
    }

    /**
     * Get is the documents checked.
     *
     * @return int
     */
    public function getIsDocumentsChecked(): int
    {
        return $this->is_documents_checked;
    }

    /**
     * Validates values on create.
     *
     * @return void
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateOnCreate(): void
    {
        $validator = new Validator;

        $db = static::getDB();

        $validator->addValidator('unique', new UniqueRule($db));

        $validation = $validator->make([
            'tin'                => $this->tin,
            'bin'                => $this->bin,
            'settlement_account' => $this->settlement_account,
            'email'              => $this->email,
        ], [
            'tin'                => 'unique:organizations,tin',
            'bin'                => 'unique:organizations,bin',
            'settlement_account' => 'unique:organizations,settlement_account',
            'email'              => 'unique:shops_admins,email',
        ]);

        $validation->setAliases([
            'tin'                => 'ИНН',
            'bin'                => 'ОГРН',
            'settlement_account' => 'Расчётный счёт',
            'email'              => 'Email',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();
        }
    }

    /**
     * Validates the passport issued date.
     *
     * @param string $passport_issued_date The passport issued date.
     *
     * @return void
     * @throws \Exception
     */
    private function validatePassportIssuedDate(string $passport_issued_date): void
    {
        $passport_issued_date = date('Y-m-d', strtotime($passport_issued_date));

        if ($passport_issued_date < '1997-10-01') {
            $this->errors[] = 'Паспорт должен быть выдан не позднее 1 октября 1997 года.';
        }

        if ($passport_issued_date >= date('Y-m-d', strtotime('tomorrow'))) {
            $this->errors[] = 'Паспорт не может быть выдан в будущем.';
        }
    }

    /**
     * Validates the age.
     *
     * @param string $birth_date The birth date.
     *
     * @return void
     * @throws \Exception
     */
    private function validateAge(string $birth_date): void
    {
        $birth_date = date('Y-m-d', strtotime($birth_date));

        $birth_date_object = DateTime::createFromFormat('Y-m-d', $birth_date);

        if ($birth_date_object->diff(new DateTime)->format('%y') < 18) {
            $this->errors[] = 'Руководитель должен быть совершеннолетним.';
        }
    }

    /**
     * Gets documents.
     *
     * @return array
     */
    private function getDocuments(): array
    {
        return [
            'document_snils'                      => $this->getDocumentSnils(),
            'document_passport'                   => $this->getDocumentPassport(),
            'document_statute_with_tax_mark'      => $this->getDocumentStatuteWithTaxMark(),
            'document_participants_decision'      => $this->getDocumentParticipantsDecision(),
            'document_bin'                        => $this->getDocumentBin(),
            'document_order_on_appointment'       => $this->getDocumentOrderOnAppointment(),
            'document_statute_of_current_edition' => $this->getDocumentStatuteOfCurrentEdition(),
            'document_contract'                   => $this->getDocumentContract(),
        ];
    }

    /**
     * Get signed documents.
     *
     * @return array
     */
    private function getSignedDocuments(): array
    {
        return [
            'document_contract'             => $this->getDocumentContract(),
            'document_questionnaire_fl_115' => $this->getDocumentQuestionnaireFl115(),
            'document_joining_application'  => $this->getDocumentJoiningApplication(),
        ];
    }

    /**
     * Creates the joining application document.
     *
     * @codeCoverageIgnore
     *
     * @param string $upload_dir
     *
     * @retrun void
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    private function createJoiningApplicationDocument(string $upload_dir): void
    {
        $document_root = SiteInfo::getDocumentRoot();

        if ($this->getType() === 'llc') {
            $template = "{$document_root}/public/documents/edited-templates/zayavlenie_o_prisoedinenii_dlya_ooo.docx";

            if (! file_exists($template)) {
                $template = "{$document_root}/public/documents/templates/zayavlenie_o_prisoedinenii_dlya_ooo.docx";
            }
        } else {
            $template = "{$document_root}/public/documents/edited-templates/zayavlenie_o_prisoedinenii_dlya_ip.docx";

            if (! file_exists($template)) {
                $template = "{$document_root}/public/documents/templates/zayavlenie_o_prisoedinenii_dlya_ip.docx";
            }
        }

        \PhpOffice\PhpWord\Settings::setTempDir("{$document_root}/tmp/");

        $templateProcessor = new TemplateProcessor($template);

        $templateProcessor->setValue('${date}', date('d.m.Y г.'));
        $templateProcessor->setValue('${organization_name}', $this->getOrganizationName());
        $templateProcessor->setValue('${tin}', $this->getTin());
        $templateProcessor->setValue('${bin}', $this->getBin());
        $templateProcessor->setValue('${fact_address}', $this->getFactAddress());
        $templateProcessor->setValue('${phone}', $this->getPhone());
        $templateProcessor->setValue('${email}', $this->getEmail());
        $templateProcessor->setValue('${settlement_account}', $this->getSettlementAccount());
        $templateProcessor->setValue('${bank_name}', $this->getBankName());
        $templateProcessor->setValue('${correspondent_account}', $this->getCorrespondentAccount());
        $templateProcessor->setValue('${bik}', $this->getBik());
        $templateProcessor->setValue('${boss_name}', $this->getBossNameAndInitials());

        if ($this->getType() === 'llc') {
            $templateProcessor->setValue('${boss_full_name}', $this->getBossFullName());
            $templateProcessor->setValue('${boss_basis_acts_full_info}', $this->getBossBasisActsFullInfo());
            $templateProcessor->setValue('${legal_address}', $this->getLegalAddress());
            $templateProcessor->setValue('${cio}', $this->getCio());
        } else {
            $templateProcessor->setValue('${boss_basis_acts_full_info}', 'Свидетельства');
            $templateProcessor->setValue('${registration_address}', $this->getRegistrationAddress());
        }

        $templateProcessor->saveAs($upload_dir . 'zayavlenie_o_prisoedinenii.docx');
    }

    /**
     * Creates the contract document.
     *
     * @codeCoverageIgnore
     *
     * @param string $upload_dir
     *
     * @retrun void
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \Exception
     */
    private function createContractDocument(string $upload_dir): void
    {
        $document_root = SiteInfo::getDocumentRoot();

        $template = "{$document_root}/public/documents/edited-templates/soglashenie.docx";

        if (! file_exists($template)) {
            $template = "{$document_root}/public/documents/templates/soglashenie.docx";
        }

        \PhpOffice\PhpWord\Settings::setTempDir("{$document_root}/tmp/");

        $templateProcessor = new TemplateProcessor($template);

        $templateProcessor->setValue('${date}', date('d.m.Y г.'));
        $templateProcessor->setValue('${organization_name}', $this->getOrganizationName());
        $templateProcessor->setValue('${boss_full_name}', $this->maybeGetBossFullNameInGenitiveCase());
        $templateProcessor->setValue('${email}', $this->getEmail());
        $templateProcessor->setValue('${bin}', $this->getBin());
        $templateProcessor->setValue('${tin}', $this->getTin());
        $templateProcessor->setValue('${fact_address}', $this->getFactAddress());
        $templateProcessor->setValue('${settlement_account}', $this->getSettlementAccount());
        $templateProcessor->setValue('${correspondent_account}', $this->getCorrespondentAccount());
        $templateProcessor->setValue('${bik}', $this->getBik());
        $templateProcessor->setValue('${bank_name}', $this->getBankName());
        $templateProcessor->setValue('${boss_name}', $this->getBossNameAndInitials());
        $templateProcessor->setValue(
            '${phone}',
            preg_replace('/[-)(\s]/', '', $this->getPhone())
        );

        if ($this->getType() === 'llc') {
            $templateProcessor->setValue('${boss_position}', $this->getBossPosition());
            $templateProcessor->setValue('${boss_basis_acts_full_info}', $this->getBossBasisActsFullInfo());
            $templateProcessor->setValue('${address}', $this->getLegalAddress());
        } else {
            $templateProcessor->setValue('${boss_position}', 'Индивидуального предпринимателя');
            $templateProcessor->setValue('${boss_basis_acts_full_info}', 'Свидетельства');
            $templateProcessor->setValue('${address}', $this->getRegistrationAddress());
        }

        $templateProcessor->saveAs($upload_dir . 'soglashenie.docx');
    }

    /**
     * Creates the questionnaire fl 115 document.
     *
     * @codeCoverageIgnore
     *
     * @param string $upload_dir
     *
     * @retrun void
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    private function createQuestionnaireFl115Document(string $upload_dir): void
    {
        $document_root = SiteInfo::getDocumentRoot();

        if ($this->getType() === 'llc') {
            $template = "{$document_root}/public/documents/edited-templates/anketa_fz_115_dlya_ooo.docx";

            if (! file_exists($template)) {
                $template = "{$document_root}/public/documents/templates/anketa_fz_115_dlya_ooo.docx";
            }
        } else {
            $template = "{$document_root}/public/documents/edited-templates/anketa_fz_115_dlya_ip.docx";

            if (! file_exists($template)) {
                $template = "{$document_root}/public/documents/templates/anketa_fz_115_dlya_ip.docx";
            }
        }

        \PhpOffice\PhpWord\Settings::setTempDir("{$document_root}/tmp/");

        $templateProcessor = new TemplateProcessor($template);

        $templateProcessor->setValue('${tin}', $this->getTin());
        $templateProcessor->setValue('${bin}', $this->getBin());
        $templateProcessor->setValue('${phone}', $this->getPhone());
        $templateProcessor->setValue('${email}', $this->getEmail());
        $templateProcessor->setValue('${license_type}', $this->getLicenseType());
        $templateProcessor->setValue('${license_number}', $this->getLicenseNumber());
        $templateProcessor->setValue('${boss_full_name}', $this->getBossFullName());
        $templateProcessor->setValue('${date}', date('d.m.Y г.'));
        $templateProcessor->setValue('${boss_name}', $this->getBossNameAndInitials());

        if ($this->getType() === 'llc') {
            $templateProcessor->setValue('${organization_name}', $this->getOrganizationName());
            $templateProcessor->setValue('${type}', 'Общество с ограниченной ответственностью');
            $templateProcessor->setValue('${legal_address}', $this->getLegalAddress());
            $templateProcessor->setValue('${boss_position}', $this->getBossPosition());
        } else {
            $templateProcessor->setValue('${boss_birth_date}', $this->getBossBirthDate());
            $templateProcessor->setValue('${boss_birth_place}', $this->getBossBirthPlace());
            $templateProcessor->setValue('${boss_passport_number}', $this->getBossPassportNumber());
            $templateProcessor->setValue('${boss_passport_issued_date}', $this->getBossPassportIssuedDate());
            $templateProcessor->setValue('${boss_passport_issued_by}', $this->getBossPassportIssuedBy());
            $templateProcessor->setValue('${boss_passport_division_code}', $this->getBossPassportDivisionCode());
            $templateProcessor->setValue('${registration_address}', $this->getRegistrationAddress());
        }

        $templateProcessor->saveAs($upload_dir . 'anketa_fz_115.docx');
    }
}
