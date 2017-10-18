<?php

namespace UKMNorge\DesignBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class SitemapController extends Controller
{
	public function cronAction()
	{
		$sitemapServ = $this->get('ukmdesign.sitemap');
		$sitemapServ->loadFromGithub(20);
		
		return $this->textResponse(
			 'Sitemap.yml is now supposed to be fetched from GitHub.com. '
			.'Failure to fetch makes the app use sitemap.yml from app install'
			."\r\n"
			.'GitHub URL: '
			."\r\n"
			. $sitemapServ->getGithubLink()
		);
	}
	
	public function textResponse( $text ) {
		$response = new Response();

		$response->setContent( $text );
		$response->setStatusCode(Response::HTTP_OK);
		$response->headers->set('Content-Type', 'text/plain');
		return $response;
	}
}
