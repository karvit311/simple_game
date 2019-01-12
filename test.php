<?php

class BaseCurrency
{
    protected $unitFrom;
    protected $unitTo;
    public $relation;

    public function __construct($from, $to)
    {
        $this->unitFrom = $from;
        $this->unitTo = $to;
        $this->relation = $this->getRelation();
    }

    protected function getRelation()
    {
        return 1;
    }

    public function render()
    {
        $className = get_class($this);
        echo "
		<div style=\"margin: 0 auto;width: 300px;\">
		<p>{$className}</p>
		<h2>{$this->unitFrom} / {$this->unitTo} => {$this->relation} / 1</h2>
		</div>
		";
    }
}

class FileCurrency extends BaseCurrency
{
    private $file;

    public function __construct($from, $to, $file)
    {
        $this->file = $file;
        parent::__construct($from, $to);
    }

    protected function getRelation()
    {
        return file_get_contents($this->file);
    }
}

class FixerCurrency extends BaseCurrency
{
    public static $unitRelation;
    private static $lastRequestTime = 0;

//    const EXPIRED_TIME_SEC = 5;

    private function getUrl()
    {
        return "http://data.fixer.io/api/latest?access_key=30262c3348b8ed57b022ac85ee0f8fd1&format=1";
    }

    protected function getRelation()
    {
       // if (empty(static::$unitRelation) or (time() - static::$lastRequestTime) > static::EXPIRED_TIME_SEC) {
            $response = file_get_contents($this->getUrl());
            $json = json_decode($response, 1);
            static::$unitRelation = $json['rates']['UAH'];
//            static::$lastRequestTime = time();
        //}

        return static::$unitRelation;
    }
    public function getTime(){
        echo  static::$lastRequestTime;
    }
}
//echo static::$lastRequestTime;

echo time();
echo "</br>";
echo "<hr><pre>";

$currency = new FixerCurrency('EUR', 'UAH');
$currency->render();
$currency->getTime();

sleep(1);

$otherCurrency = new FileCurrency('EUR', 'UAH',ROOT . '/application/file.php');
$otherCurrency->render();

sleep(6);

$lastCurrency = new BaseCurrency('EUR', 'UAH');
$lastCurrency->render();

echo "</pre>";
$datetime1 = new DateTime('2009-10-11 12:20:01');
$date1 = date_format($datetime1, 'Y-m-d H:i:s');
$datetime2 = new DateTime('2009-10-13 13:00:00');
$interval = $datetime1->diff($datetime2);
echo $interval->days.' days total<br>';
echo $interval->y.' years<br>';
echo $interval->m.' months<br>';
echo $interval->d.' days<br>';
echo $interval->h.' hours<br>';
echo $interval->i.' minutes<br>';
echo $interval->s.' seconds<br>';
echo 'dif= '.$interval->days.' days '.$interval->h.' hours '.$interval->i.' minutes '.$interval->s.' seconds <br>';
// echo date_format($interval, ' Y-m-d G:ia');
echo $interval->format('%R%a дней G%:%i%a%');