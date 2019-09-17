<?php

namespace App\Models;

use App\FileUploader;
use App\SiteInfo;
use App\UniqueRule;
use Core\Model;
use DateTime;
use PDO;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Client.
 *
 * @package App\Models
 */
class Client extends Model
{
    /**
     * Error messages.
     *
     * @var array
     */
    private $errors = [];

    /**
     * The id.
     *
     * @var int|null
     */
    private $id;

    /**
     * The last name.
     *
     * @var string|null
     */
    private $last_name;

    /**
     * The first name.
     *
     * @var string|null
     */
    private $first_name;

    /**
     * The middle name.
     *
     * @var string|null
     */
    private $middle_name;

    /**
     * The birth date.
     *
     * @var string|null
     */
    private $birth_date;

    /**
     * The birth place.
     *
     * @var string|null
     */
    private $birth_place;

    /**
     * The sex.
     *
     * @var string|null
     */
    private $sex;

    /**
     * Is the last name changed.
     *
     * @var int
     */
    private $is_last_name_changed = 0;

    /**
     * The previous last name.
     *
     * @var string|null
     */
    private $previous_last_name;

    /**
     * The tin.
     *
     * @var string|null
     */
    private $tin;

    /**
     * The snils.
     *
     * @var string|null
     */
    private $snils;

    /**
     * The passport number.
     *
     * @var string|null
     */
    private $passport_number;

    /**
     * The passport division code.
     *
     * @var string|null
     */
    private $passport_division_code;

    /**
     * The passport issued by.
     *
     * @var string|null
     */
    private $passport_issued_by;

    /**
     * The passport issued date.
     *
     * @var string|null
     */
    private $passport_issued_date;

    /**
     * The workplace.
     *
     * @var string|null
     */
    private $workplace;

    /**
     * The salary.
     *
     * @var int|null
     */
    private $salary;

    /**
     * The registration zip code.
     *
     * @var string|null
     */
    private $reg_zip_code;

    /**
     * The registration city.
     *
     * @var string|null
     */
    private $reg_city;

    /**
     * The registration street.
     *
     * @var string|null
     */
    private $reg_street;

    /**
     * The registration building.
     *
     * @var string|null
     */
    private $reg_building;

    /**
     * The registration apartment.
     *
     * @var string|null
     */
    private $reg_apartment;

    /**
     * Is the address matched.
     *
     * @var int
     */
    private $is_address_matched = 0;

    /**
     * The fact zip code.
     *
     * @var string|null
     */
    private $fact_zip_code;

    /**
     * The fact city.
     *
     * @var string|null
     */
    private $fact_city;

    /**
     * The fact street.
     *
     * @var string|null
     */
    private $fact_street;

    /**
     * The fact building.
     *
     * @var string|null
     */
    private $fact_building;

    /**
     * The fact apartment.
     *
     * @var string|null
     */
    private $fact_apartment;

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
     * The additional phone.
     *
     * @var string|null
     */
    private $additional_phone;

    /**
     * The photo of a passport main spread.
     *
     * @var UploadedFile|null
     */
    private $photo_passport_main_spread;

    /**
     * The photo of a client face with a passport main spread.
     *
     * @var UploadedFile|null
     */
    private $photo_client_face_with_passport_main_spread;

    /**
     * The Client constructor.
     *
     * @param array $data (optional) Initial property values.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Finds an client model by the id.
     *
     * @param int $id The id.
     *
     * @return mixed The client object if found, false otherwise.
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM clients WHERE id = :id LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Finds an client model by the phone.
     *
     * @param string $phone The client phone.
     *
     * @return mixed The client object if found, false otherwise.
     */
    public static function findByPhone(string $phone)
    {
        $phone = preg_replace('/[-)+(\s]/', '', $phone);

        $sql = 'SELECT * FROM clients WHERE phone = :phone LIMIT 1';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);

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
        $sql = 'DELETE FROM clients WHERE id = :id';

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Gets the path of a photo passport main spread.
     *
     * @param string $client_phone The client phone number.
     *
     * @return string
     */
    public static function getPathOfPhotoPassportMainSpread(string $client_phone): string
    {
        $file = glob(SiteInfo::getDocumentRoot() . '/uploads/client-photos/phone-' . $client_phone
            . '/fotografiya_glavnogo_razvorota_pasporta.*');

        return ! empty($file) ? $file[0] : '';
    }

    /**
     * Gets the path of a photo client face with passport main spread.
     *
     * @param string $client_phone The client phone number.
     *
     * @return string
     */
    public static function getPathOfPhotoClientFaceWithPassportMainSpread(string $client_phone): string
    {
        $file = glob(SiteInfo::getDocumentRoot() . '/uploads/client-photos/phone-' . $client_phone
            . '/fotografiya_klienta_s_pasportom.*');

        return ! empty($file) ? $file[0] : '';
    }

    /**
     * Creates the client.
     *
     * @return bool True if success, false otherwise.
     * @throws \Exception
     */
    public function create(): bool
    {
        $this->validateOnCreate();

        if (! empty($this->errors)) {
            return false;
        }

        $this->uploadPhotos();

        $this->birth_date           = date('Y-m-d', strtotime($this->birth_date));
        $this->passport_issued_date = date('Y-m-d', strtotime($this->passport_issued_date));

        $this->validateAge($this->birth_date);
        $this->validatePassportIssuedDate($this->passport_issued_date);

        if (! empty($this->errors)) {
            return false;
        }

        $sql = 'INSERT INTO clients (last_name, first_name, middle_name, birth_date, birth_place, sex, 
                     is_last_name_changed, previous_last_name, tin, snils, passport_number, passport_division_code, 
                     passport_issued_by, passport_issued_date, workplace, salary, reg_zip_code, reg_city, reg_street, 
                     reg_building, reg_apartment, is_address_matched, fact_zip_code, fact_city, fact_street, 
                     fact_building, fact_apartment, email, phone, additional_phone) 
                     VALUES (:last_name, :first_name, :middle_name, :birth_date, :birth_place, :sex, 
                             :is_last_name_changed, :previous_last_name, :tin, :snils, :passport_number, 
                             :passport_division_code, :passport_issued_by, :passport_issued_date, :workplace, 
                             :salary, :reg_zip_code, :reg_city, :reg_street, :reg_building, :reg_apartment, 
                             :is_address_matched, :fact_zip_code, :fact_city, :fact_street, :fact_building, 
                             :fact_apartment, :email, :phone, :additional_phone)';

        $this->additional_phone = preg_replace('/[-)+(\s]/', '', $this->additional_phone);

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $this->middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':birth_date', $this->birth_date, PDO::PARAM_STR);
        $stmt->bindValue(':birth_place', $this->birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':sex', $this->sex, PDO::PARAM_STR);
        $stmt->bindValue(':is_last_name_changed', $this->is_last_name_changed, PDO::PARAM_INT);
        $stmt->bindValue(':previous_last_name', $this->previous_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':tin', $this->tin, PDO::PARAM_STR);
        $stmt->bindValue(':snils', $this->snils, PDO::PARAM_STR);
        $stmt->bindValue(':passport_number', $this->passport_number, PDO::PARAM_STR);
        $stmt->bindValue(':passport_division_code', $this->passport_division_code, PDO::PARAM_STR);
        $stmt->bindValue(':passport_issued_by', $this->passport_issued_by, PDO::PARAM_STR);
        $stmt->bindValue(':passport_issued_date', $this->passport_issued_date, PDO::PARAM_STR);
        $stmt->bindValue(':reg_zip_code', $this->reg_zip_code, PDO::PARAM_STR);
        $stmt->bindValue(':reg_city', $this->reg_city, PDO::PARAM_STR);
        $stmt->bindValue(':reg_street', $this->reg_street, PDO::PARAM_STR);
        $stmt->bindValue(':reg_building', $this->reg_building, PDO::PARAM_STR);
        $stmt->bindValue(':workplace', $this->workplace, PDO::PARAM_STR);
        $stmt->bindValue(':salary', $this->salary, PDO::PARAM_INT);
        $stmt->bindValue(':reg_apartment', $this->reg_apartment, PDO::PARAM_STR);
        $stmt->bindValue(':is_address_matched', $this->is_address_matched, PDO::PARAM_INT);
        $stmt->bindValue(':fact_zip_code', $this->fact_zip_code, PDO::PARAM_STR);
        $stmt->bindValue(':fact_city', $this->fact_city, PDO::PARAM_STR);
        $stmt->bindValue(':fact_street', $this->fact_street, PDO::PARAM_STR);
        $stmt->bindValue(':fact_building', $this->fact_building, PDO::PARAM_STR);
        $stmt->bindValue(':fact_apartment', $this->fact_apartment, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
        $stmt->bindValue(':additional_phone', $this->additional_phone, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();

            return true;
        }

        $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     *  Updates the client record.
     *
     * @param array $data
     *
     * @return bool
     * @throws \Rakit\Validation\RuleQuashException
     * @throws \Exception
     */
    public function update(array $data): bool
    {
        $previous_tin             = $this->tin;
        $previous_snils           = $this->snils;
        $previous_passport_number = $this->passport_number;
        $previous_email           = $this->email;

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->validateOnUpdate($previous_tin, $previous_snils, $previous_passport_number, $previous_email);

        if (! empty($this->errors)) {
            return false;
        }

        $this->uploadPhotos();

        $this->birth_date           = date('Y-m-d', strtotime($this->birth_date));
        $this->passport_issued_date = date('Y-m-d', strtotime($this->passport_issued_date));

        $this->validateAge($this->birth_date);
        $this->validatePassportIssuedDate($this->passport_issued_date);

        if (! empty($this->errors)) {
            return false;
        }

        $sql = 'UPDATE clients SET last_name = :last_name, first_name = :first_name, middle_name = :middle_name, 
                       birth_date = :birth_date, birth_place = :birth_place, sex = :sex, 
                       is_last_name_changed = :is_last_name_changed, previous_last_name = :previous_last_name, 
                       tin = :tin, snils = :snils, passport_number = :passport_number, 
                       passport_division_code = :passport_division_code, passport_issued_by = :passport_issued_by, 
                       passport_issued_date = :passport_issued_date, workplace = :workplace, salary = :salary, 
                       reg_zip_code = :reg_zip_code, reg_city = :reg_city, reg_street = :reg_street, 
                       reg_building = :reg_building, reg_apartment = :reg_apartment, 
                       is_address_matched = :is_address_matched, fact_zip_code = :fact_zip_code, 
                       fact_city = :fact_city, fact_street = :fact_street, fact_building = :fact_building, 
                       fact_apartment = :fact_apartment, email = :email, additional_phone = :additional_phone
                    WHERE id = :id';

        $this->additional_phone = preg_replace('/[-)+(\s]/', '', $this->additional_phone);

        $db   = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $this->middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':birth_date', $this->birth_date, PDO::PARAM_STR);
        $stmt->bindValue(':birth_place', $this->birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':sex', $this->sex, PDO::PARAM_STR);
        $stmt->bindValue(':is_last_name_changed', $this->is_last_name_changed, PDO::PARAM_INT);
        $stmt->bindValue(':previous_last_name', $this->previous_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':tin', $this->tin, PDO::PARAM_STR);
        $stmt->bindValue(':snils', $this->snils, PDO::PARAM_STR);
        $stmt->bindValue(':passport_number', $this->passport_number, PDO::PARAM_STR);
        $stmt->bindValue(':passport_division_code', $this->passport_division_code, PDO::PARAM_STR);
        $stmt->bindValue(':passport_issued_by', $this->passport_issued_by, PDO::PARAM_STR);
        $stmt->bindValue(':passport_issued_date', $this->passport_issued_date, PDO::PARAM_STR);
        $stmt->bindValue(':workplace', $this->workplace, PDO::PARAM_STR);
        $stmt->bindValue(':salary', $this->salary, PDO::PARAM_INT);
        $stmt->bindValue(':reg_zip_code', $this->reg_zip_code, PDO::PARAM_STR);
        $stmt->bindValue(':reg_city', $this->reg_city, PDO::PARAM_STR);
        $stmt->bindValue(':reg_street', $this->reg_street, PDO::PARAM_STR);
        $stmt->bindValue(':reg_building', $this->reg_building, PDO::PARAM_STR);
        $stmt->bindValue(':reg_apartment', $this->reg_apartment, PDO::PARAM_STR);
        $stmt->bindValue(':is_address_matched', $this->is_address_matched, PDO::PARAM_INT);
        $stmt->bindValue(':fact_zip_code', $this->fact_zip_code, PDO::PARAM_STR);
        $stmt->bindValue(':fact_city', $this->fact_city, PDO::PARAM_STR);
        $stmt->bindValue(':fact_street', $this->fact_street, PDO::PARAM_STR);
        $stmt->bindValue(':fact_building', $this->fact_building, PDO::PARAM_STR);
        $stmt->bindValue(':fact_apartment', $this->fact_apartment, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':additional_phone', $this->additional_phone, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        $this->errors[] = 'Не удалось обновить данные, попробуйте ещё раз.'; // @codeCoverageIgnore

        return false; // @codeCoverageIgnore
    }

    /**
     * Gets the data for a profile.
     *
     * @return array The data for profile.
     */
    public function getDataForProfile(): array
    {
        return [
            'last_name'              => $this->getLastName(),
            'first_name'             => $this->getFirstName(),
            'middle_name'            => $this->getMiddleName(),
            'birth_date'             => date('Y-m-d', strtotime($this->getBirthDate())),
            'birth_place'            => $this->getBirthPlace(),
            'sex'                    => $this->getSex(),
            'is_last_name_changed'   => $this->getIsLastNameChanged(),
            'previous_last_name'     => $this->getPreviousLastName(),
            'tin'                    => $this->getTin(),
            'snils'                  => $this->getSnils(),
            'passport_number'        => $this->getPassportNumber(),
            'passport_division_code' => $this->getPassportDivisionCode(),
            'passport_issued_by'     => $this->getPassportIssuedBy(),
            'passport_issued_date'   => date('Y-m-d', strtotime($this->getPassportIssuedDate())),
            'workplace'              => $this->getWorkplace(),
            'salary'                 => $this->getSalary(),
            'reg_zip_code'           => $this->getRegZipCode(),
            'reg_city'               => $this->getRegCity(),
            'reg_street'             => $this->getRegStreet(),
            'reg_building'           => $this->getRegBuilding(),
            'reg_apartment'          => $this->getRegApartment(),
            'is_address_matched'     => $this->getIsAddressMatched(),
            'fact_zip_code'          => $this->getFactZipCode(),
            'fact_city'              => $this->getFactCity(),
            'fact_street'            => $this->getFactStreet(),
            'fact_building'          => $this->getFactBuilding(),
            'fact_apartment'         => $this->getFactApartment(),
            'email'                  => $this->getEmail(),
            'phone'                  => $this->getPhone(),
            'additional_phone'       => $this->getAdditionalPhone(),
        ];
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
     * Gets the last name.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * Gets the first name.
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Gets the middle name.
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    /**
     * Gets the birth date.
     *
     * @return string|null
     */
    public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    /**
     * Gets the sex.
     *
     * @return string|null
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * Gets is the last name changed.
     *
     * @return int
     */
    public function getIsLastNameChanged(): int
    {
        return $this->is_last_name_changed;
    }

    /**
     * Gets the previous last name.
     *
     * @return string|null
     */
    public function getPreviousLastName(): ?string
    {
        return $this->previous_last_name;
    }

    /**
     * Gets the birth place.
     *
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return $this->birth_place;
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
     * Gets the snils.
     *
     * @return string|null
     */
    public function getSnils(): ?string
    {
        return $this->snils;
    }

    /**
     * Gets the passport number.
     *
     * @return string|null
     */
    public function getPassportNumber(): ?string
    {
        return $this->passport_number;
    }

    /**
     * Gets the passport division code.
     *
     * @return string|null
     */
    public function getPassportDivisionCode(): ?string
    {
        return $this->passport_division_code;
    }

    /**
     * Gets the passport issued by.
     *
     * @return string|null
     */
    public function getPassportIssuedBy(): ?string
    {
        return $this->passport_issued_by;
    }

    /**
     * Gets the passport issued date.
     *
     * @return string|null
     */
    public function getPassportIssuedDate(): ?string
    {
        return $this->passport_issued_date;
    }

    /**
     * Gets the workplace.
     *
     * @return string|null
     */
    public function getWorkplace(): ?string
    {
        return $this->workplace;
    }

    /**
     * Gets the salary.
     *
     * @return int|null
     */
    public function getSalary(): ?int
    {
        return $this->salary;
    }

    /**
     * Gets the registration zip code.
     *
     * @return string|null
     */
    public function getRegZipCode(): ?string
    {
        return $this->reg_zip_code;
    }

    /**
     * Gets the registration city.
     *
     * @return string|null
     */
    public function getRegCity(): ?string
    {
        return $this->reg_city;
    }

    /**
     * Gets the registration street.
     *
     * @return string|null
     */
    public function getRegStreet(): ?string
    {
        return $this->reg_street;
    }

    /**
     * Gets the registration building.
     *
     * @return string|null
     */
    public function getRegBuilding(): ?string
    {
        return $this->reg_building;
    }

    /**
     * Gets the registration apartment.
     *
     * @return string|null
     */
    public function getRegApartment(): ?string
    {
        return $this->reg_apartment;
    }

    /**
     * Gets is the address matched.
     *
     * @return int
     */
    public function getIsAddressMatched(): int
    {
        return $this->is_address_matched;
    }

    /**
     * Gets the fact zip code.
     *
     * @return string|null
     */
    public function getFactZipCode(): ?string
    {
        return $this->fact_zip_code;
    }

    /**
     * Gets the fact city.
     *
     * @return string|null
     */
    public function getFactCity(): ?string
    {
        return $this->fact_city;
    }

    /**
     * Gets the fact street.
     *
     * @return string|null
     */
    public function getFactStreet(): ?string
    {
        return $this->fact_street;
    }

    /**
     * Gets the fact building.
     *
     * @return string|null
     */
    public function getFactBuilding(): ?string
    {
        return $this->fact_building;
    }

    /**
     * Gets the fact apartment.
     *
     * @return string|null
     */
    public function getFactApartment(): ?string
    {
        return $this->fact_apartment;
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
     * Gets the additional phone.
     *
     * @return string|null
     */
    public function getAdditionalPhone(): ?string
    {
        return $this->additional_phone;
    }

    /**
     * Gets the photo of a passport main spread.
     *
     * @return UploadedFile|null
     */
    public function getPhotoPassportMainSpread(): ?UploadedFile
    {
        return $this->photo_passport_main_spread;
    }

    /**
     * Gets the photo of a client face with a passport main spread.
     *
     * @return UploadedFile|null
     */
    public function getPhotoClientFaceWithPassportMainSpread(): ?UploadedFile
    {
        return $this->photo_client_face_with_passport_main_spread;
    }

    /**
     * Validates on create.
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
            'tin'             => $this->tin,
            'snils'           => $this->snils,
            'passport_number' => $this->passport_number,
            'email'           => $this->email,
        ], [
            'tin'             => 'unique:clients,tin',
            'snils'           => 'unique:clients,snils',
            'passport_number' => 'unique:clients,passport_number',
            'email'           => 'unique:clients,email',
        ]);

        $validation->setAliases([
            'tin'             => 'ИНН',
            'snils'           => 'СНИЛС',
            'passport_number' => 'Серия и номер паспорта',
            'email'           => 'Email',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();
        }
    }

    /**
     * Validates on update.
     *
     * @param string $previous_tin
     * @param string $previous_snils
     * @param string $previous_passport_number
     * @param string $previous_email
     *
     * @return void
     * @throws \Rakit\Validation\RuleQuashException
     */
    private function validateOnUpdate(
        string $previous_tin,
        string $previous_snils,
        string $previous_passport_number,
        string $previous_email
    ): void {
        $validator = new Validator;

        $db = static::getDB();

        $validator->addValidator('unique', new UniqueRule($db));

        $validation = $validator->make([
            'tin'             => $this->tin,
            'snils'           => $this->snils,
            'passport_number' => $this->passport_number,
            'email'           => $this->email,
        ], [
            'tin'             => 'unique:clients,tin,' . $previous_tin,
            'snils'           => 'unique:clients,snils,' . $previous_snils,
            'passport_number' => 'unique:clients,passport_number,' . $previous_passport_number,
            'email'           => 'unique:clients,email,' . $previous_email,
        ]);

        $validation->setAliases([
            'tin'             => 'ИНН',
            'snils'           => 'СНИЛС',
            'passport_number' => 'Серия и номер паспорта',
            'email'           => 'Email',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->errors = $validation->errors()->all();
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
     * Uploads photos.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    private function uploadPhotos(): bool
    {
        $file_uploader = new FileUploader(
            SiteInfo::getDocumentRoot() . '/uploads/client-photos/phone-' . $this->getPhone()
        );
        $file_uploader->upload($this->getPhotoPassportMainSpread(), 'fotografiya_glavnogo_razvorota_pasporta');
        $file_uploader->upload($this->getPhotoClientFaceWithPassportMainSpread(), 'fotografiya_klienta_s_pasportom');

        return true;
    }
}
