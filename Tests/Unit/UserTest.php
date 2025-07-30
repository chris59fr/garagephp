<?php

namespace Tests\Unit;

use App\Models\User;
use InvalidArgumentException;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;


/**
 * @covers \App\Models\User
 */

class UserTest extends TestCase{

    public function testSetUsernameWithValidUsernamesSetsProperty():void{


        //1 Préparation
        $user = new User();
        $username = 'testuser';

        //2Action
        $user->setUsername($username);

        //3 Assertion (Vérification)
        $this->assertEquals($username, $user->getUsername());

    }

    public function testSetEmailWithInvalidEmailThrowsException(): void{


        //On s'attend à ce qu'une exception soit lancée
        $this->expectException(InvalidArgumentException::class);

        //1 Préparation
        $user = new User();

        //2 Action
        $user->setEmail('email-invalide');
    }

    public function testSaveWhenCreatingNewUserReturnsTrue(): void{


        //1 Préparation: Créer des mocks pour PDO et PDOStatement
        // On simule le comportement de la BDD sans s'y connecter
        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoStatementMock->expects($this->once()) //On s'attend à ce que execute() soit appelé une fois
                         ->method('execute')
                         ->willReturn(true); // Et qu'elle retourne true

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($pdoStatementMock); // prepare() doit retourner notre mock de statement

        $pdoMock->expects($this->once())
                ->method('lastInsertId')
                ->willReturn('1'); // lastInsertId() doit retourner un ID

        //2 Action: Créer un user et on va appeler la méthode save()
        // On injecte notre fausse BDD dans le ,constructeur( mais cela nécéssite une petite adaptation de notre modèle)
        $user = new User($pdoMock);
        $user->setUsername('john.doe')
             ->setEmail('test@johndoe.com')
             ->setPassword('Password123');

        $result = $user->save();

        //3 Assertion
        $this->assertTrue($result);
        $this->assertEquals(1, $user->getId());
    }
}