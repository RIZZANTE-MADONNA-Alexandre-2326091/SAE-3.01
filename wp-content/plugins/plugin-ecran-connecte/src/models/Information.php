<?php

namespace Models;

use JsonSerializable;
use PDO;

/**
 * Class Information
 *
 * Information entity
 *
 * @package Models
 */
class Information extends Model implements Entity, JsonSerializable
{

    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var User
     */
    private User $author;

    /**
     * @var string
     */
    private string $creationDate;

    /**
     * @var string
     */
    private string $expirationDate;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var string (Text | Image | excel | PDF | Event | Video (short ou classique))
     */
    private string $type;

    /**
     * @var int
     */
    private int $adminId;

    /**
     * Insert an information in the database
     *
     * @return string
     */
    public function insert(): string
    {
        $database = $this->getDatabase();
        $request = $database->prepare("INSERT INTO ecran_information (title, content, creation_date, expiration_date, type, author, administration_id) VALUES (:title, :content, :creationDate, :expirationDate, :type, :userId, :administration_id) ");

        $request->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $request->bindValue(':content', $this->getContent(), PDO::PARAM_STR);
        $request->bindValue(':creationDate', $this->getCreationDate(), PDO::PARAM_STR);
        $request->bindValue(':expirationDate', $this->getExpirationDate(), PDO::PARAM_STR);
        $request->bindValue(':type', $this->getType(), PDO::PARAM_STR);
        $request->bindValue(':userId', $this->getAuthor(), PDO::PARAM_INT);
        $request->bindValue('administration_id', $this->getAdminId(), PDO::PARAM_INT);

        $request->execute();

        return $database->lastInsertId();
    }

    /**
     * Modify an information
     *
     * @return int
     */
    public function update(): int
    {
        $request = $this->getDatabase()->prepare("UPDATE ecran_information SET title = :title, content = :content, expiration_date = :expirationDate WHERE id = :id");

        $request->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $request->bindValue(':content', $this->getContent(), PDO::PARAM_STR);
        $request->bindValue(':expirationDate', $this->getExpirationDate(), PDO::PARAM_STR);
        $request->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        $request->execute();

        return $request->rowCount();
    }

    /**
     * Delete an information in the database
     */
    public function delete()
    {
        $request = $this->getDatabase()->prepare('DELETE FROM ecran_information WHERE id = :id');

        $request->bindValue(':id', $this->getId(), PDO::PARAM_INT);

        $request->execute();

        return $request->rowCount();
    }

    /**
     * Return an information corresponding to the ID
     *
     * @param $id   int id
     *
     * @return Information | bool
     */
    public function get($id): Information|bool|static
    {
        $request = $this->getDatabase()->prepare("SELECT id, title, content, creation_date, expiration_date, author, type, administration_id FROM ecran_information WHERE id = :id LIMIT 1");

        $request->bindParam(':id', $id, PDO::PARAM_INT);

        $request->execute();

        if ($request->rowCount() > 0)
        {
            return $this->setEntity($request->fetch(PDO::FETCH_ASSOC));
        }
        return false;
    }

    /**
     * @param int $begin
     * @param int $numberElement
     *
     * @return Information|array
     */
    public function getList(int $begin = 0, int $numberElement = 25): Information|array
    {
        $request = $this->getDatabase()->prepare("SELECT id, title, content, creation_date, expiration_date, author, type, administration_id FROM ecran_information ORDER BY id ASC LIMIT :begin, :numberElement");

        $request->bindValue(':begin', (int)$begin, PDO::PARAM_INT);
        $request->bindValue(':numberElement', (int)$numberElement, PDO::PARAM_INT);

        $request->execute();

        if ($request->rowCount() > 0)
        {
            return $this->setEntityList($request->fetchAll());
        }
        return [];
    }

    /**
     * Return the list of information created by a user
     *
     * @param User $author
     * @param int $begin
     * @param int $numberElement
     * @return Information|array
     */
    public function getAuthorListInformation(User $author, int $begin = 0, int $numberElement = 25): Information|array
    {
        $request = $this->getDatabase()->prepare('SELECT id, title, content, creation_date, expiration_date, author, type, administration_id FROM ecran_information WHERE author = :author ORDER BY expiration_date LIMIT :begin, :numberElement');

        $request->bindParam(':author', $author, PDO::PARAM_INT);
        $request->bindValue(':begin', (int)$begin, PDO::PARAM_INT);
        $request->bindValue(':numberElement', (int)$numberElement, PDO::PARAM_INT);

        $request->execute();

        return $this->setEntityList($request->fetchAll(PDO::FETCH_ASSOC));
    } //getAuthorListInformation()

    public function countAll()
    {
        $request = $this->getDatabase()->prepare("SELECT COUNT(*) FROM ecran_information");

        $request->execute();

        return $request->fetch()[0];
    }

    /**
     * Return the list of event present in database
     * @return array|null|object
     */
    public function getListInformationEvent(): object|array|null
    {
        $request = $this->getDatabase()->prepare('SELECT id, title, content, creation_date, expiration_date, author, type FROM ecran_information WHERE type = "event" ORDER BY expiration_date ASC');

        $request->execute();

        return $this->setEntityList($request->fetchAll(PDO::FETCH_ASSOC));
    }


    /**
     * @return Information|array
     */
    public function getFromAdminWebsite(): Information|array
    {
        $request = $this->getDatabaseViewer()->prepare('SELECT id, title, content, type, author, expiration_date, creation_date FROM ecran_information LIMIT 200');

        $request->execute();

        return $this->setEntityList($request->fetchAll(), true);
    }

    /**
     *
     * @return Information|array
     */
    public function getAdminWebsiteInformation(): Information|array
    {
        $request = $this->getDatabase()->prepare('SELECT id, title, content, creation_date, expiration_date, author, type, administration_id FROM ecran_information WHERE administration_id IS NOT NULL LIMIT 500');

        $request->execute();

        return $this->setEntityList($request->fetchAll());
    }

    /**
     * @param int $id
     * @return $this|bool|Information
     */
    public function getInformationFromAdminSite(int $id): Information|bool|static
    {
        $request = $this->getDatabaseViewer()->prepare('SELECT id, title, content, type, author, expiration_date, creation_date FROM ecran_information WHERE id = :id LIMIT 1');

        $request->bindValue(':id', $id, PDO::PARAM_INT);

        $request->execute();

        if ($request->rowCount() > 0) {
            return $this->setEntity($request->fetch(), true);
        }
        return false;
    }

    /**
     * Build a list of informations
     *
     * @param $dataList
     *
     * @return array | Information
     */
    public function setEntityList($dataList, bool $adminSite = false): array|Information
    {
        $listEntity = array();
        foreach ($dataList as $data) {
            $listEntity[] = $this->setEntity($data, $adminSite);
        }
        return $listEntity;
    }


    /**
     * Create an information
     *
     * @param $data
     *
     * @return $this
     */
    public function setEntity($data, bool $adminSite = false): Information|static
    {
        $entity = new Information();
        $author = new User();

        $entity->setId($data['id']);
        $entity->setTitle($data['title']);
        $entity->setContent($data['content']);
        $entity->setCreationDate(date('Y-m-d', strtotime($data['creation_date'])));
        $entity->setExpirationDate(date('Y-m-d', strtotime($data['expiration_date'])));

        $entity->setType($data['type']);

        if ($data['administration_id'] != null) {
            $author->setLogin('Administration');
            $entity->setAuthor($author);
        } else {
            $entity->setAuthor($author->get($data['author']));
        }

        if ($adminSite) {
            $entity->setAdminId($data['id']);
        } else {
            $entity->setAdminId($data['administration_id']);
        }

        return $entity;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param $id int
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param $title string
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param $author User
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate(mixed $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     */
    public function setExpirationDate(mixed $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param $content string
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param $type string
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getAdminId(): int
    {
        return $this->adminId;
    }

    /**
     * @param int $adminId
     */
    public function setAdminId(int $adminId): void
    {
        $this->adminId = $adminId;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
