<?php

namespace nexuscore\FormAPI\elements;

use nexuscore\FormAPI\window\SimpleWindowForm;
use nexuscore\FormAPI\window\WindowForm;

class Button extends Element
{

    /** @var ButtonImage */
    private $image = null;

    public function __construct(String $name, String $text, WindowForm $form, ButtonImage $image = null)
    {
        parent::__construct($form, $name, $text);
        $this->image = $image;

        $this->content = [
            "text" => $this->text
        ];

        if($this->image !== null) {
            $this->content["image"] = [
                "type" => $this->image->getType(),
                "data" => $this->image->getLocation()
            ];
        }
    }

    /**
     * @return ButtonImage
     */
    public function getImage(): ?ButtonImage
    {
        return $this->image;
    }

}