<?php

namespace App\Service\Export;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use function Stringy\create as s;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class WordExportStrategy implements ExportStrategyInterface
{
    private $key = 'docx';

    public function __construct()
    {

    }

    public function isDownloadable(string $format): bool
    {
        return $format === $this->key;
    }

    public function download($activity, $group)
    {
        $properties = $this->getProperties($activity);
        $filteredProperties = $this->filterPropertiesByGroup($properties, $group);
        $activityProperties = $this->collectProperties($activity, $filteredProperties);
        $temp_file = $this->writeWordDoc($activityProperties);
        return new BinaryFileResponse($temp_file);
    }

    /**
     * Get all the properties on the submitted activity
     *
     * @param $activity
     * @return \ReflectionProperty[]
     * @throws \ReflectionException
     */
    private function getProperties($activity): array
    {
        $rc = new \ReflectionClass(get_class($activity));
        return $rc->getProperties();
    }

    /**
     * Filter all the submitted properties by the group annotation value submitted
     *
     * @param $properties
     * @param $group
     * @return array Array of the properties that are members of the submitted group
     */
    private function filterPropertiesByGroup($properties, $group): array
    {
        $pattern = '/"'.$group.'"/';
        $filteredProperties = [];

        foreach ($properties as $property) {
            $comment = $property->getDocComment();
            $property->setAccessible(true);
            if (preg_match('/@Groups\(({.+})\)/', $comment, $match)) {
                $groups = explode("'",$match[1]);
                if (preg_grep($pattern, $groups)){
                    // @Todo may only need the name of the property
                    array_push($filteredProperties, $property);
                }
            }
        }
        return $filteredProperties;
    }

    /**
     * Using the properties in th requested group get the values for the submitted activity
     *
     * @param $activity
     * @param $filteredProperties
     * @return array
     */
    private function collectProperties($activity, $filteredProperties): array
    {
        $activityProperties = [];
        foreach ($filteredProperties as $property) {

            // Resolve standard parameters or ArrayCollection (relations)
            if ($property->getValue($activity) instanceof \Doctrine\ORM\PersistentCollection) {
                $p = $property->getValue($activity)->toArray();
            } elseif ($property->getValue($activity) instanceof \DateTime) {
                $p = $property->getValue($activity)->format('Y-m-d H:i:s');
            } else {
                $p = $property->getValue($activity);
            }
            $activityProperties[$property->getName()] = $p;
        }
        return $activityProperties;
    }

    /**
     * Write out the requested properties form the submitted activity to a word file
     *
     * @param $activityProperties
     * @return false|string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function writeWordDoc($activityProperties)
    {
        $wordDocument = new PhpWord();
        $wordDocument->addTitleStyle(1, array('bold' => true, 'size' => 16, 'allCaps' => true,), array('spaceAfter' => 150));
        $wordDocument->addTitleStyle(2, array('bold' => true), array('spaceAfter' => 100));
        $fileName = str_replace(" ", "_", $activityProperties['name']);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $section = $wordDocument->addSection();
        $section->addTitle(
            $activityProperties["name"],
            1
        );

        foreach ($activityProperties as $key => $value) {

            if ($key === 'name') {
                continue;
            }

            // Turn the camelCase property name into plain text
            $pattern = '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/';
            $title = preg_replace($pattern, ' $0', $key);
            $title = (string) S($title)->toLowerCase()->upperCaseFirst();
            $section->addTitle(
                $title,
                2
            );

            if (is_string($value)) {
                $section->addText($value);
            } elseif (is_array($value)) {
                // Use variable function to avoid deep if/else statement
                $this->$key($value, $section);
            }

            $section->addTextBreak(1);
        }

        $objWriter = IOFactory::createWriter($wordDocument, 'Word2007');
        $objWriter->save($temp_file);
        return $temp_file;
    }

    private function directions($value, $section): void
    {
        foreach ($value as $direction) {
            $section->addText($direction->getPosition());
            $section->addText($direction->getDirection());
        }
    }

    private function tags()
    {

    }

    private function flags()
    {

    }

    private function images()
    {

    }

    private function collections()
    {

    }

    private function adminNotes()
    {

    }
}