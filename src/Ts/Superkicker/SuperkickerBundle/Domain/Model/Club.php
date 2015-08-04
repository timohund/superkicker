<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Represents a sports club
 *
 * this entity was compiled from Webforge\Doctrine\Compiler
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="clubs")
 */
class Club extends CompiledClub {


	private $temp;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $logoFile;

	public function getLogoAbsolutePath() {
		return null === $this->logoPath
				? null
				: $this->getUploadRootDir() . '/' . $this->logoPath;
	}

	public function getLogoWebPath() {
		return null === $this->logoPath
				? null
				: $this->getUploadDir() . '/' . $this->logoPath;
	}

	protected function getUploadRootDir() {
		// the absolute directory path where uploaded
		// documents should be saved
		return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
	}

	protected function getUploadDir() {
		// get rid of the __DIR__ so it doesn't screw up
		// when displaying uploaded doc/image in the view.
		return '/uploads/documents';
	}


	/**
	 * Sets file.
	 *
	 * @param UploadedFile $logoFile
	 */
	public function setLogoFile(UploadedFile $logoFile = null) {
		$this->logoFile = $logoFile;
		// check if we have an old image path
		if (isset($this->logoPath)) {
			// store the old name to delete after the update
			$this->temp = $this->logoPath;
			$this->logoPath = null;
		} else {
			$this->logoPath = 'initial';
		}
	}

	/**
	 * Get file.
	 *
	 * @return UploadedFile
	 */
	public function getLogoFile() {
		return $this->logoFile;
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if (null !== $this->getLogoFile()) {
			// do whatever you want to generate a unique name
			$filename = sha1(uniqid(mt_rand(), true));
			$this->logoPath = $filename . '.' . $this->getLogoFile()->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		if (null === $this->getLogoFile()) {
			return;
		}

		// if there is an error when moving the file, an exception will
		// be automatically thrown by move(). This will properly prevent
		// the entity from being persisted to the database on error

		$this->getLogoFile()->move($this->getUploadRootDir(), $this->logoPath);

		// check if we have an old image
		if (isset($this->temp)) {
			// delete the old image
			unlink($this->getUploadRootDir() . '/' . $this->temp);
			// clear the temp image path
			$this->temp = null;
		}
		$this->logoFile = null;
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		$file = $this->getLogoAbsolutePath();
		if ($file) {
			unlink($file);
		}
	}
}
