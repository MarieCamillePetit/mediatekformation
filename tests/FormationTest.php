<?php

namespace App\Tests;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


use App\Entity\Formation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;
use DateTimeInterface;
/**
 * Description of FormationTest
 *
 * @author petit
 */
class FormationTest extends TestCase{
    
    public function testGetDateparutionString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2021-06-26"));
        $this->assertEquals("26/06/2021", $formation->getPublishedAtString());
    }
}
