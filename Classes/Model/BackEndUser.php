<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Model;

use OliverKlee\Oelib\Email\ConvertableToMimeAddressTrait;
use OliverKlee\Oelib\Interfaces\ConvertableToMimeAddress;
use OliverKlee\Oelib\Interfaces\MailRole;

/**
 * This class represents a back-end user.
 */
class BackEndUser extends AbstractModel implements MailRole, ConvertableToMimeAddress
{
    use ConvertableToMimeAddressTrait;

    /**
     * @var array<string, string> the user's configuration (unserialized)
     */
    private $configuration = [];

    /**
     * Gets this user's username.
     *
     * @return string this user's username, will not be empty for valid users
     */
    public function getUserName(): string
    {
        return $this->getAsString('username');
    }

    /**
     * Gets this user's real name.
     *
     * @return string the user's real name, will not be empty for valid records
     */
    public function getName(): string
    {
        return $this->getAsString('realName');
    }

    /**
     * Gets the user's e-mail address.
     *
     * @return string the e-mail address, might be empty
     */
    public function getEmailAddress(): string
    {
        return $this->getAsString('email');
    }

    /**
     * Gets this user's language. Will be a two-letter "lg_typo3" key of the
     * "static_languages" table or "default" for the default language.
     *
     * @return non-empty-string this user's language key
     */
    public function getLanguage(): string
    {
        $configuration = $this->getConfiguration();
        $languageConfiguration = $configuration['lang'] ?? '';
        $result = $languageConfiguration !== '' ? $languageConfiguration : $this->getDefaultLanguage();

        return ($result !== '') ? $result : 'default';
    }

    /**
     * Sets this user's default language.
     *
     * @param non-empty-string $language this user's language key, must be a two-letter "lg_typo3" key of
     *        the "static_languages" table or "default" for the default language
     */
    public function setDefaultLanguage(string $language): void
    {
        // @phpstan-ignore-next-line We are explicitly checking for a contract violation here.
        if ($language === '') {
            throw new \InvalidArgumentException('$language must not be empty.', 1331488621);
        }

        $this->setAsString(
            'lang',
            ($language !== 'default') ? $language : ''
        );
    }

    /**
     * Checks whether this user has a non-default language set.
     */
    public function hasLanguage(): bool
    {
        return $this->getLanguage() !== 'default';
    }

    /**
     * Retrieves the user's configuration, and unserializes it.
     *
     * @return array<string, string> the user's configuration, will be empty if the user has no configuration set
     */
    private function getConfiguration(): array
    {
        if ($this->configuration === []) {
            $this->configuration = (array)\unserialize($this->getAsString('uc'), ['allowed_classes' => false]);
        }

        return $this->configuration;
    }

    /**
     * Returns the user's default language.
     *
     * @return string the user's default language, will be empty if no default language has been set
     */
    private function getDefaultLanguage(): string
    {
        return $this->getAsString('lang');
    }
}
