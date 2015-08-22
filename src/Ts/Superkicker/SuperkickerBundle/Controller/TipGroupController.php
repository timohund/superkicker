<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;


class TipGroupController extends AbstractController {
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction() {


		return $this->templating->renderResponse(
				'SuperkickerBundle:TipGroup:edit.html.twig'
		);
	}
}