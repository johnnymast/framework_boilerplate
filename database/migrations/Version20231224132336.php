<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224132336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ascii_art (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1E531A5EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ascii_categories (ascii_id INT UNSIGNED NOT NULL, category_id INT NOT NULL, INDEX IDX_92ADCCB7618F7999 (ascii_id), INDEX IDX_92ADCCB712469DE2 (category_id), PRIMARY KEY(ascii_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ascii_tags (ascii_id INT UNSIGNED NOT NULL, tag_id INT NOT NULL, INDEX IDX_8F8B1BF4618F7999 (ascii_id), INDEX IDX_8F8B1BF4BAD26311 (tag_id), PRIMARY KEY(ascii_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, weight INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3AF34668A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_credentials (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, service VARCHAR(128) NOT NULL, oauth_token LONGTEXT NOT NULL, expires DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A62484A5D8344B2A (oauth_token), INDEX IDX_A62484A5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passkey_credentials (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, storage_id VARCHAR(128) NOT NULL, credential VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_1D67AC5E5CC5DB90 (storage_id), INDEX IDX_1D67AC5EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6FBC9426A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_ascii (tag_id INT NOT NULL, ascii_id INT UNSIGNED NOT NULL, INDEX IDX_F3D6C3FFBAD26311 (tag_id), INDEX IDX_F3D6C3FF618F7999 (ascii_id), PRIMARY KEY(tag_id, ascii_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, activated TINYINT(1) NOT NULL, verification_token VARCHAR(255) NOT NULL, password_token VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9C1CC006B (verification_token), UNIQUE INDEX UNIQ_1483A5E9BEAB6C24 (password_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ascii_art ADD CONSTRAINT FK_1E531A5EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ascii_categories ADD CONSTRAINT FK_92ADCCB7618F7999 FOREIGN KEY (ascii_id) REFERENCES ascii_art (id)');
        $this->addSql('ALTER TABLE ascii_categories ADD CONSTRAINT FK_92ADCCB712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE ascii_tags ADD CONSTRAINT FK_8F8B1BF4618F7999 FOREIGN KEY (ascii_id) REFERENCES ascii_art (id)');
        $this->addSql('ALTER TABLE ascii_tags ADD CONSTRAINT FK_8F8B1BF4BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE oauth_credentials ADD CONSTRAINT FK_A62484A5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE passkey_credentials ADD CONSTRAINT FK_1D67AC5EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC9426A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tag_ascii ADD CONSTRAINT FK_F3D6C3FFBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_ascii ADD CONSTRAINT FK_F3D6C3FF618F7999 FOREIGN KEY (ascii_id) REFERENCES ascii_art (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ascii_art DROP FOREIGN KEY FK_1E531A5EA76ED395');
        $this->addSql('ALTER TABLE ascii_categories DROP FOREIGN KEY FK_92ADCCB7618F7999');
        $this->addSql('ALTER TABLE ascii_categories DROP FOREIGN KEY FK_92ADCCB712469DE2');
        $this->addSql('ALTER TABLE ascii_tags DROP FOREIGN KEY FK_8F8B1BF4618F7999');
        $this->addSql('ALTER TABLE ascii_tags DROP FOREIGN KEY FK_8F8B1BF4BAD26311');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668A76ED395');
        $this->addSql('ALTER TABLE oauth_credentials DROP FOREIGN KEY FK_A62484A5A76ED395');
        $this->addSql('ALTER TABLE passkey_credentials DROP FOREIGN KEY FK_1D67AC5EA76ED395');
        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC9426A76ED395');
        $this->addSql('ALTER TABLE tag_ascii DROP FOREIGN KEY FK_F3D6C3FFBAD26311');
        $this->addSql('ALTER TABLE tag_ascii DROP FOREIGN KEY FK_F3D6C3FF618F7999');
        $this->addSql('DROP TABLE ascii_art');
        $this->addSql('DROP TABLE ascii_categories');
        $this->addSql('DROP TABLE ascii_tags');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE oauth_credentials');
        $this->addSql('DROP TABLE passkey_credentials');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tag_ascii');
        $this->addSql('DROP TABLE users');
    }
}
