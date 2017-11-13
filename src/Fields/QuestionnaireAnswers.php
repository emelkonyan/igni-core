<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Contracts\SourceModel;

/**
 * Class Select.
 */
class QuestionnaireAnswers extends Field
{
    /**
     * @var SourceModel
     */
    protected $sourceModel;

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        $options = Array("" => "Both", "bump_buddy" => "Bump buddy", "baby_buddy" => "Baby buddy");

        return $options;
    }

    /**
     * @return SourceModel
     *
     * @throws \Exception
     */
    public function getSourceModel()
    {
        if (! isset($this->sourceModel) && ($sourceModel = $this->getOptions('sourceModel'))) {
            if (class_exists($sourceModel)) {
                $this->sourceModel = new $sourceModel();
                if (! $this->sourceModel instanceof SourceModel) {
                    throw new \Exception('Source model for field '.$this->getFieldName().' must implement '.SourceModel::class);
                }
            } else {
                throw new \Exception('Source model is missing for field '.$this->getFieldName());
            }
        }

        return $this->sourceModel;
    }
}
