<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Medium.
 * @ORM\Entity
 */
class Medium
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * A human friendly filename
     * @ORM\Column(type="string", nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string")
     */
    private $filename;

    /**
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @ORM\Column(type="string")
     */
    private $relativePath;

    /**
     * @ORM\Column(type="string")
     */
    private $filetype;

    /**
     * @var UploadedFile
     */
    private $uploadedFile;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Medium
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Medium
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Medium
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set filetype
     *
     * @param string $filetype
     *
     * @return Medium
     */
    public function setFiletype($filetype)
    {
        $this->filetype = $filetype;

        return $this;
    }

    /**
     * Get filetype
     *
     * @return string
     */
    public function getFiletype()
    {
        return $this->filetype;
    }

    /**
     * Set uploadedFile.
     *
     * @param UploadedFile $uploadedFile
     *
     * @return Medium
     */
    public function setUploadedFile($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * Get uploadedFile.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Has uploadedFile.
     *
     * @return bool
     */
    public function hasUploadedFile()
    {
        return $this->uploadedFile ? true : false;
    }

    /**
     * Set relativePath
     *
     * @param string $relativePath
     *
     * @return Medium
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * Get relativePath
     *
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }
}
