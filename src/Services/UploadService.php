<?php


  namespace App\Services;


  use Symfony\Component\HttpFoundation\File\UploadedFile;

  class UploadService
  {
    private $targetDirectory;

    public function __construct(string $targetDirectory) {

      $this->targetDirectory = $targetDirectory;
    }

    public function uploadFile(UploadedFile $file) {

      $fileNameOld = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

      $fileName = $fileNameOld.'-'.uniqid().'.'.$file->guessExtension();

      $file->move($this->getTargetDirectory(), $fileName);

      return $fileName;
    }

    public function getTargetDirectory() {
      return $this->targetDirectory;
    }
  }