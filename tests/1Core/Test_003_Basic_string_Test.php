<?php
declare(strict_types=1);

namespace TestWorld;
use PHPUnit\Framework\TestCase;
use Gfd\Core\Gfd_SimpleInits_Interface;
use Gfd\Core\Gfd_SimpleInits_Implementation;


class Test_003_Basic_string_Test extends TestCase {

      function testType()
      {
          try {
              $gfd = new Gfd1(); // see below
              $gfd->value = 1;
              $this->assertTrue(false, "Should not see this. Only make by Init, etc. ");
          } catch (\TypeError $e) {
              $this->assertTrue(true, "Should  see this: " . get_called_class());
          }
      }

    function testType2()
    {
        try {
            $gfd = Gfd1::InitByCorrectArray();
            $this->assertTrue(false, "Should not see this: ");
        } catch (\ArgumentCountError $e) {
            $this->assertTrue(true, "Should  see this: " . get_called_class());
        }

        // No fancy checking.  Don't screw it up
        $gfd = Gfd1::InitByCorrectArray([]);
        $this->assertTrue(true, "Should  see this: " . get_called_class());

        // But runtime php will stop you before initializaiton
        try {
            $s = $gfd->value;
            $this->assertTrue(false, "Should not see this: ");
        } catch (\Error $e) {
            $this->assertTrue(true, "Should  see this: " . get_called_class());
        }

    }


}

class Gfd1 implements Gfd_SimpleInits_Interface {
    use Gfd_SimpleInits_Implementation;
    public ?string $value;

}
