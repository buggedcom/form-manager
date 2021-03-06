<?php
namespace FormManager\Inputs;

use FormManager\InputInterface;

class Select extends Input implements InputInterface {
	public static $error_message = 'This value is not a valid';

	protected $name = 'select';
	protected $close = true;
	protected $options = [];
	protected $value;

	public function options (array $options = null) {
		if ($options === null) {
			return $this->options;
		}

		$this->options = $options;

		return $this;
	}

	public function val ($value = null) {
		if ($value === null) {
			return $this->value;
		}

		if ($this->attr('multiple') && !is_array($value)) {
			$value = array($value);
		}

		$this->value = $value;

		return $this;
	}

	public function validate () {
		$value = $this->val();

		if (!empty($value)) {
			if ($this->attr('multiple') && is_array($value)) {
				foreach ($value as $val) {
					if (!isset($this->options[$val])) {
						$this->error(static::$error_message);
						return false;
					}
				}
			} else if (!isset($this->options[$value])) {
				$this->error(static::$error_message);
				return false;
			}
		}

		return parent::validate();
	}

	public function html ($html = null) {
		$val = $this->val();
		$html = '';

        foreach ($this->options as $value => $label) {
            $html .= '<option value="'.static::escape($value).'"';
            
            if ($val == $value || (is_array($val) && in_array($value, $val))) {
                $html .= ' selected';
            }

            $html .= '>'.$label.'</option>';
        }

        return $html;
	}
}
