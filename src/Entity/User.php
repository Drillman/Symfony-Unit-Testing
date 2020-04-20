<?php

namespace App\Entity;

use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="UserAccount")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Todolist", inversedBy="user_id")
     */
    private $todolist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function isValid(): bool
    {
        if (!$this->getFirstname() || !$this->getName() || !$this->getEmail() || !$this->getAge()) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" is not valid dude',
                    $this->getAge(),$this->getName(),$this->getAge(),$this->getEmail()
                )
            );
        }
        if (strlen($this->getPassword()) > 40 || strlen($this->getPassword()) < 8) {
            throw new InvalidArgumentException(
                'The password must be understood in 8 and 40'
            );
        }
        if ($this->getAge() < 13) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" too young bro',$this->getAge()  
                )
            );
        }
        return true;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTodolist(): ?Todolist
    {
        return $this->todolist;
    }

    public function setTodolist(?Todolist $todolist): self
    {
        $this->todolist = $todolist;

        return $this;
    }
}
