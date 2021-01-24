<?php

/*
Copyright (c) 2003, Brian Takita
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

  * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
  * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
  * Neither the name of Brian Takita nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
* @access public
* Integer type that supports large numbers, conversion to number formats up to base 36, and arithmitic in up to 36 base numbers.
* @author Brian Takita <brian.takita@runbox.com>
*/
class Integer
{
  /**
  * @access private
  * The value of the integer.
  */
  var $_val;
  /**
  * @access private
  * The numeric base of the integer.
  */
  var $_base;
  /**
  * @access private
  * Is this a negative number?
  */
  var $_negative;

  /**
  * @access protected
  * The constructor. Sets the integer value.
  * @param int/String $val The Integer value.
  * @param int    $base The base of this number.
  */
  protected function __construct ($val=0, $base=10) {
    $this->set($val, $base);
  }

  /**
  * @access public
  * Sets the integer value.
  * @param int/String $val The Integer value.
  * @param int    $base The base of this number.
  */
  public function set($val, $base=10) {
    if ($val[0] == '-') {
      $this->_negative = true;
    }

    $this->_base = $base;

    if (is_int($val)) {
      $val = ''.$val;
    }
    $this->_val = $val;
  }

  /**
  * @access public
  * Gets the value in decimal format.
  * @return String The decimal value.
  */
  public function get() {
    return $this->_val;
  }

  /**
  * @access public
  * Gets the base the integer is in.
  * @return int The base the integer is in.
  */
  public function getBase() {
    return $this->_base;
  }

  /**
  * @access public
  * Convert the integer into binary format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in binary format.
  */
  public function toBin($len=0) {
    return str_pad(Integer::convert($this->_val, $this->_base, 2), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the integer into binary format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in binary format.
  */
  public function to12($len=0) {
    return str_pad(Integer::convert($this->_val, 10, 12), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the integer into binary format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in binary format.
  */
  public function from12($len=0) {
    return str_pad(Integer::convert($this->_val, 12, 10), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the integer into octal format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in octal format.
  */
  public function toOct($len=0) {
    return str_pad(Integer::convert($this->_val, $this->_base, 8), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the integer into decimal format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in decimal format.
  */
  public function toDec($len=0) {
    // Convert to string.
    return str_pad(Integer::convert($this->_val, $this->_base, 10), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the integer into hex format.
  * @param int $len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
  * @return The Integer in hex format.
  */
  public function toHex($len=0) {
    //return str_pad(strtoupper(dechex($this->_val)), $len, '0', STR_PAD_LEFT);
    return str_pad(Integer::convert($this->_val, $this->_base, 16), $len, '0', STR_PAD_LEFT);
  }

  /**
  * @access public
  * Convert the number from one number base to another up to 36 base. This is a Class method. An object does not need to be instantiated for this to be used. Usage is Integer::convert($val, $inSys, $outSys)
  * @param int/String $input   The number to be converted.
  * @param int    $inputBase The Input base system.
  * @param int    $outputBase The Output base system.
  * @return String         The converted number.
  */
  public static function convert($input, $inputBase=10, $outputBase=10) {
    if ($inputBase == $outputBase) {
      return $input;
    }

    // If '0', return val.
    if ($input == '0') {
      return $input;
    }

    $output = '';
    $divmod = array();

    $outBaseInInBase = ltrim(Integer::_convertSingle($outputBase, $inputBase), '0');

    while(1) {
      if (Integer::compare($input, $outBaseInInBase) < 0) {
        $output = Integer::_convertSingle($input, $outputBase) . $output;
        break;
      }

      $divmod = Integer::divmod($input, $outBaseInInBase, $inputBase);

      $r = $divmod['mod'];

      $input = $divmod['div'];

      $output = Integer::_convertSingle($r, $outputBase) . $output;
    }

    return $output;
  }

  /**
  * @access private
  * Converts a single integer digit into the appropriate number base system..
  * @param int $in   The Integer Digit in any base system.
  * @param int $base The system that the Integer digit will be converted to.
  * @return String   The converted digit.
  */
  private function _convertSingle($in, $base) {
    if ($in < $base) {
      return Integer::_baseVal($in);
    }

    $outVal = '';
    while($in > 0) {
      $r = $in % $base;
      $outVal = Integer::_baseVal($r) . $outVal;
      $in = (int)$in/$base;
    }

    return $outVal;
  }

  /**
  * @access public
  * Compares two Integers to see which is greater or if they are equal.
  * @param String $a The first number to be compared.
  * @param String $b The second number to be compared.
  * @return int    If $a > $b return 1.<br>If $a < $b return -1.<br>If $a == $b return 0.
  */
  public function compare($a, $b) {
    $lenA = strlen($a);
    $lenB = strlen($b);
    $len = ($lenA > $lenB) ? $lenA : $lenB;

    $a = str_pad($a, $len, '0', STR_PAD_LEFT);
    $b = str_pad($b, $len, '0', STR_PAD_LEFT);

    if ($a < $b) {
      return -1;
    } elseif ($a > $b) {
      return 1;
    } elseif ($a == $b) {
      return 0;
    }
  }

  /**
  * @access public
  * Adds two numbers. The numbers must be in the same base system.
  * @param int/String $a  The number on the left side of the add.
  * @param int/String $b  The number on the right side of the add.
  * @param int    $base The base system where the addition will take place.
  * @return int        The sum.
  */
  public function add($a, $b, $base=10) {
    $negA = ($a[0] == '-') ? true : false;
    $negB = ($b[0] == '-') ? true : false;

    $a = strtoupper($a);
    $b = strtoupper($b);

    // Get rid of nonvalid characters.
    $a = Integer::_trimString($a, $base);
    $b = Integer::_trimString($b, $base);

    // Handle negative numbers.
    if ($negA === true && $negB === false) {
      return Integer::sub($b, $a, $base);
    } elseif ($negA === false && $negB === true) {
      return Integer::sub($a, $b, $base);
    } elseif ($negA === true && $negB === true) {
      return '-'.Integer::add($a, $b, $base);
    }

    $lenA = strlen($a);
    $lenB = strlen($b);

    $len = ($lenA > $lenB) ? $lenA : $lenB;

    $a = str_pad($a, $len, '0', STR_PAD_LEFT);
    $b = str_pad($b, $len, '0', STR_PAD_LEFT);

    $i = $len - 1;
    $c = 0;
    $sum = '';

    // Add up all the numbers.
    while($i >= 0 || $c > 0) {
      if ($i >= 0) {
        // Get the current number.
        $valA = Integer::_decVal($a[$i]);
        $valB = Integer::_decVal($b[$i]);
      } else {
        // We are past the range of the two added numbers.
        $valA = 0;
        $valB = 0;
      }

      $r = $valA + $valB + $c;
      if ($r < $base) {
        $c = 0;
      } else {
        $c = 1;
        $r = ($r - $base);
      }
      // Convert to base.
      $r = Integer::_baseVal($r);

      $sum = $r . $sum;
      $i--;
    }
    return $sum;
  }

  /**
  * @access public
  * Subtract two numbers. The numbers must be in the same base system.
  * @param int/String $a  The number to be subtracted from.
  * @param int/String $b  The subtractor.
  * @param int    $base The base system where the subtraction will take place.
  * @return int       The difference.
  */
  public function sub($a, $b, $base = 10) {
    $negA = ($a[0] == '-') ? true : false;
    $negB = ($b[0] == '-') ? true : false;

    $a = strtoupper($a);
    $b = strtoupper($b);

    // Get rid of nonvalid characters.
    $a = Integer::_trimString($a, $base);
    $b = Integer::_trimString($b, $base);

    // Handle negative numbers.
    if ($negA === true && $negB === false) {
      return '-'.Integer::add($a, $b, $base);
    } elseif ($negA === false && $negB === true) {
      return Integer::add($a, $b, $base);
    } elseif ($negA === true && $negB === true) {
      return Integer::sub($b, $a, $base);
    }

    $lenA = strlen($a);
    $lenB = strlen($b);

    $len = ($lenA > $lenB) ? $lenA : $lenB;

    $a = str_pad($a, $len, '0', STR_PAD_LEFT);
    $b = str_pad($b, $len, '0', STR_PAD_LEFT);

    // Make sure first arg is a larger number.
    if ($b > $a) {
      return '-'.Integer::sub($b, $a, $base);
    }

    $c = false;
    $difference = '';
    for ($i=$len-1; $i >= 0; $i--) {
      if ($c === false) {
        $valA = Integer::_decVal($a[$i]);
      }
      $valB = Integer::_decVal($b[$i]);

      $r = $valA - $valB;

      // Is $r not negative?
      if ($r >= 0) {
        // $r is not negative. Set carry to false.
        $c = false;
      } else {
        // $r is negative. Carry down the number
        $valA = Integer::_decVal($a[$i-1]) - 1;

        // Carry increases $r by the system value.
        $r += $base;

        // Set carry to true;
        $c = true;
      }

      $r = Integer::_baseVal($r);

      $difference = $r . $difference;
    }
    $difference = ltrim($difference, '0');

    return ($difference != '') ? $difference : '0';
  }

  /**
  * @access public
  * Multiplies two numbers. The numbers must be in the same base system.
  * @param int/String $a  The number on the left side of the multiplication.
  * @param int/String $b  The number on the right side of the multiplication.
  * @param int    $base The base system where the multiplication will take place.
  * @return int       The product.
  */
  public function mul($a, $b, $base=10) {
    $negA = ($a[0] == '-') ? true : false;
    $negB = ($b[0] == '-') ? true : false;

    $a = strtoupper($a);
    $b = strtoupper($b);

    // Get rid of nonvalid characters.
    $a = Integer::_trimString($a, $base);
    $b = Integer::_trimString($b, $base);

    if ($negA == '-' xor $negB == '-') {
      return '-'.Integer::mul($a, $b, $base);
    }

    $lenA = strlen($a);
    $lenB = strlen($b);

    // $b is supposed to be shorter
    if ($lenB > $lenA) {
      return Integer::mul($b, $a, $base);
    }

    // The total product
    $prod = '0';
    // Cycle through all $b numbers
    for ($i = 0; $i < $lenB; $i++) {
      $valB = Integer::_decVal($b[($lenB - 1) - $i]);

      $val = '';
      $c = 0;
      $j = $lenA -1;
      // Multiply $b cycled through all $a numbers
      while($j >= 0 || $c > 0) {
        // If $a still has characters, get one.
        $valA = ($j < 0) ? 0 : Integer::_decVal($a[$j]);

        $res = $valA * $valB + $c;

        if ($res < $base) {
          $c = 0;
          $val = Integer::_baseVal($res) . $val;
        } else {
          $c = (int)($res / $base);
          $val = Integer::_baseVal($res % $base) . $val;
        }
        $j--;
      }
      // Pad $val with 0's behind it. Then Add $val to $prod.
      $prod = Integer::add($val.str_pad('', $i, 0), $prod, $base);
    }
    return $prod;
  }

  /**
  * @access public
  * Divides two numbers. Returns the quotient. The numbers must be in the same base system.
  * @param int/String $a  The numerator.
  * @param int/String $b  The denominator.
  * @param int    $base The base system where the addition will take place.
  * @return int       The Quotient.
  */
  public function div($a, $b, $base = 10) {
    $d = Integer::divmod($a, $b, $base);

    return $d['div'];
  }

  /**
  * @access public
  * Divides two numbers. Returns the remainder. The numbers must be in the same base system.
  * @param int/String $a  The numerator.
  * @param int/String $b  The denominator.
  * @param int    $base The base system where the addition will take place.
  * @return int       The Remainder.
  */
  public function mod($a, $b, $base = 10) {
    $d = Integer::divmod($a, $b, $base);

    return $d['mod'];
  }

  /**
  * @access public
  * Divides two numbers. Returns an array of the quotient and the remainder.
  * @param int/String $a  The numerator.
  * @param int/String $b  The denominator.
  * @param int    $base The base system where the addition will take place.
  * @return int[]      The array containing the quotient and remainder. Array is array('div' => quotient, 'mod' => remainder).
  */
  public function divmod($a, $b, $base) {
    $negA = ($a[0] == '-') ? true : false;
    $negB = ($b[0] == '-') ? true : false;

    $a = strtoupper($a);
    $b = strtoupper($b);

    // Get rid of nonvalid characters.
    $a = Integer::_trimString($a, $base);
    $b = Integer::_trimString($b, $base);

    if (Integer::compare($a, $b) == -1) {
      return '0';
    }

    $len = strlen($a);

    $quot = '';
    $r = '';

    for($i=0; $i < $len; $i++) {
      $r .= $a[$i];

      $cVal = 0;

      if (Integer::compare($r, $b) >= 0) {
        // Subtract until $r < $b
        do {
          $cVal++;

          // See if the next subtr
          $r = Integer::sub($r, $b, $base);
        } while(Integer::compare($r, $b) >= 0);
      }
      $quot .= Integer::_baseVal($cVal);
    }

    $quot = ltrim($quot, '0');
    $r = ltrim($r, '0');

    if ($quot == '') {
      $quot = '0';
    }

    if ($r == '') {
      $r = '0';
    }

    // Is this a negative product?
    if ($negA xor $negB) {
      $quot = '-'.$quot;
    }

    return array(
          'div' => $quot,
          'mod' => $r
          );
  }

  /**
  * @access private
  * Removes characters that are not part of the base number system from the string.
  * @param String $string The number.
  * @param int  $base  The base number system.
  * @return String     The converted number.
  */
  private function _trimString($string, $base) {
    if ($base <= 10) {
      $rep = '/[^0-'.($base-1).']/';
    } elseif ($base > 10 && $base <= 36) {
      $rep = '/[^0-9A-'.chr(ord('A') + ($base-11)).']/';
    } else {
      echo 'Error: Cant go above a 36 number system.<br>';
      return false;
    }
    return preg_replace($rep, '', $string);
  }

  /**
  * @access private
  * Gets the decimal value of the character in a certain number format.
  * @param char $chr The number in its original base format.
  * @return int    The base10 equivalent.
  */
  private function _decVal($chr) {
    if ($chr >= '0' && $chr <= '9') {
      return ord($chr) - ord('0');
    } elseif ($chr >= 'A' && $chr <= 'Z') {
      return 10 + (ord($chr) - ord('A'));
    } else {
      return false;
    }
  }

  /**
  * @access private
  * Gets the equivalent character from a base10 number.
  * @param int $val The number to be converted.
  * @return char   The converted number in up to base36.
  */
  private function _baseVal($val) {
    if ($val >= 0 && $val <= 9) {
      return ''.$val;
    } elseif ($val >= 10 && $val <= 36) {
      return chr(ord('A') + ($val-10));
    } else {
      return false;
    }
  }
}
?>
