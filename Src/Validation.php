<?php
namespace App\Src;

use App\Models\AppModel;
use DateTime;
use PDO;

/**
 * Class Validation
 * @package App\Src
 */
class Validation
{

    /**
     * @var array
     */
    private $params;
    /**
     * @var array
     */
    private $errors;

    /**
     * @var string[]
     */
    private $messages = [
        'required' => 'Le champs %s est requis',
        'empty' => 'Le champs %s ne peut être vide',
        'minLength' => 'Le champs %s doit contenir plus de %d caractères',
        'maxLength' => 'Le champs %s doit contenir moins de %d caractères',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caractères',
        'datetime' => 'Le champs %s doit être une date valide (%d)',
        'exists' => 'Le champs %s n\'existe pas dans la table %s',
        'unique' => 'Le champs %s doit être unique',
        'filetype' => 'Le champs %s n\'est pas au format valide',
        'uploaded' => 'Vous devez uploader un fichier',
        'email' => 'Cet email ne semble pas valide',
        'confirm' => 'Une erreur est survenue dans la confirmation du champs %s'
    ];

    /**
     * Validation constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {

        $this->params = $request->getData();
        $this->secure_inputs();
        $this->errors = [];
    }

    /**
     * Vérifie que les champs passés en paramètre sont bien requis
     * @param string ...$keys
     * @return Validation
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     * @param string ...$keys
     * @return Validation
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    /**
     * Contrôle si la taille du champs est respecté
     * @param string $key
     * @param int $min
     * @param int $max
     * @return Validation
     */
    public function length(string $key, int $min, int $max = null): self
    {
        $value = $this->getValue($key);
        $length = strlen($value);
        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) &&
            $length < $min
        ) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) &&
            $length > $max
        ) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    /**
     * Vérifie si l'email est valide
     * @param string $key
     * @return Validation
     */
    public function email(string $key): self
    {
        $value = $this->getValue($key);
        if (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $value)) {
            $this->addError($key, 'email');
        }
        return $this;
    }

    /**
     * Vérifie si un champs a bien été confirmé
     * @param string $key
     * @return Validation
     */
    public function confirm(string $key): self
    {
        $value = $this->getValue($key);
        $valueConfirm = $this->getValue($key.'_confirm');
        if ($value !== $valueConfirm) {
            $this->addError($key, 'confirm');
        }
        return $this;
    }

    /**
     * Vérifie si le champs est une date au bon format
     * @param string $key
     * @param string $format
     * @return Validation
     */
    public function dateTime(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        $value = $this->getValue($key);
        $date = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    /**
     * Vérifie si que la clé est unique dans la base de données
     * @param string $key
     * @param AppModel|string $model
     * @param PDO|null $pdo
     * @param int|null $exclude
     * @return Validation
     */
    public function unique(string $key, $model, PDO $pdo = null, int $exclude = null): self
    {
        if ($model instanceof AppModel) {
            $pdo = $model->getDb()->getPdo();
            $table = $model->getTable();
        }
        $value = $this->getValue($key);
        $query = "SELECT id FROM {$table} WHERE {$key} = ?";
        $params = [$value];
        if (!is_null($exclude)) {
            $query .= " AND id != ?";
            $params[] = $exclude;
        }
        $statement = $pdo->prepare($query);
        $statement->execute($params);
        if ($statement->fetchColumn() !== false) {
            $this->addError($key, 'unique', [$value]);
        }
        return $this;
    }

    /**
     * Vérifie le format du fichier
     * @param string $key
     * @return Validation
     */
    public function extension(string $key): self
    {
        if (!empty($_FILES) && (strpos($_FILES['file']['type'], 'image') !== false))
        {
            $this->addError($key, 'filetype', 'image');
        }
        return $this;
    }

    /**
     * Retournr le tableau d'erreurs
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Renvoie True si l'ensemble des vérifications ne génère pas d'erreurs
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Ajoute un message d'erreur dans le tableau d'erreur
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key, string $rule, array $attributes = [])
    {
        $params[0] = $this->messages[$rule];
        $params[1] = $key;
        if (!empty($attributes))
        {
            $params = array_merge($params, $attributes);
        }
        $this->errors[$key] = (string) call_user_func_array('sprintf', $params);
    }

    /**
     * Récupère la valeur correspondant à la clé dans le tableau $params
     * @param string $key
     * @return mixed|null
     */
    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }

    /**
     * Permet de sécuriser les données
     */
    private function secure_inputs()
    {
        foreach ($this->params as $key => $data)
        {
            $this->params[$key] = trim($data);
            $this->params[$key] = stripcslashes($data);
            $this->params[$key] = htmlspecialchars($data);
        }
    }
}
