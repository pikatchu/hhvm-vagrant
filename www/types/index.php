<?hh

require "/vagrant/www/xhp/php-lib/init.php";

set_error_handler(function ($no, $str) {
  $func = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
  $line = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['line'];
  echo <p>ERROR(calling "{$func}()" on l.{$line}) : {$str}</p>;
});

function echo_upper(string $s) {
    echo (strtoupper($s));
}

echo_upper("lower"); // LOWER
echo_upper(5);  // ERROR(calling "echo_upper()" on l.16) : Argument 1 passed to echo_upper() must be an instance of string, int given
echo_upper(null); // ERROR(calling "echo_upper()" on l.17) : Argument 1 passed to echo_upper() must be an instance of string, null given

function sum(array<int> $a) {
    return array_reduce($a, ($sum, $i) ==> $sum + $i);
}

echo <p>sum([1, 2, 3]) = {sum([1, 2, 3])}</p>; // sum([1, 2, 3]) = 6

class Generics<T as countable> {
    private ?T $t;

    public function get() : T {
        return $this->t;
    }

    public function set(?T $t) {
        $this->t = $t;
    }

    public function __toString() {
        return var_export($this->t, true);
    }

    public function count() {
        return $this->t->count();
    }
}

$type = new Generics<Vector<int>>();
$type->set(Vector {1, 2, 3});

echo <div>$type
    <ul>
        <li>= {$type}</li> <!-- HH\Vector { 1, 2, 3, } -->
        <li>size = {$type->count()}</li> <!-- 3 -->
    </ul></div>;

// To keep performance at a top level, there is no runtime type check
// Those programming errors would be caught by the (yet to be release)
// static analyzer
sum(["a", "b"]);
$type->set("No check");
