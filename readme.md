## frobou-validator ##
validate some data, like min value, max value, etc.
data must be on stdClass object.

**usage:**

    $this->data = [];
    $this->object = new \stdClass();
    $this->validator = new FrobouValidator();
    public function testValidateDateEnOk()
    {
        $this->data['date_en'] = [];
        $this->object->dt_1 = '2015-11-22';
        $this->object->dt_2 = '2016-02-09';
        array_push($this->data['date_en'], clone $this->object, ['dt_1', 'dt_2']);
        $this->assertTrue($this->validator->validate(['date_en'], $this->data, true));
    }

    public function testValidateDateEnFail()
    {
        $this->data['date_en'] = [];
        $this->object->dt_1 = '1';
        $this->object->dt_2 = '1';
        array_push($this->data['date_en'], clone $this->object, ['dt_1', 'dt_2']);
        $this->assertArrayHasKey('date_en', $this->validator->validate(['date_en'], $this->data, true));
    }

**sample data:**

    array_push($this->data['integer'], clone $this->object, ['start', 'qtty']);
    array_push($this->data['struct'], clone $this->object, ['start', 'qtty', 'pastel'], ['pimponeta']); //['pimponeta'] is an optional array
    array_push($this->data['required'], clone $this->object, ['start', 'qtty', 'pastel']);
    array_push($this->data['values'], clone $this->object, ['pastel'], ['carne', 'queijo', 'ovo']);
    array_push($this->data['maxlen'], clone $this->object, ['pastel' => 12, 'start' => 3]);
    array_push($this->data['minlen'], clone $this->object, ['pastel' => 5, 'start' => 2]);
    array_push($this->data['min'], $this->object, ['start' => 1, 'qtty' => 1]);
    array_push($this->data['max'], $this->object, ['start' => 1, 'qtty' => '1']);
    array_push($this->data['email'], clone $this->object, ['email']);
    array_push($this->data['ip'], clone $this->object, ['ip_1', 'ip_2']);
    array_push($this->data['date_en'], clone $this->object, ['dt_1', 'dt_2']);
