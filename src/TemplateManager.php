<?php

namespace Kata;

use Kata\Context\ApplicationContext;
use Kata\Entity\Quote;
use Kata\Entity\Template;
use Kata\Entity\User;
use Kata\Repository\DestinationRepository;
use Kata\Repository\QuoteRepository;
use Kata\Repository\SiteRepository;

class TemplateManager
{
    public function getTemplateComputed(Template $template, array $data)
    {
        if (!$template) {
            throw new \RuntimeException('no template given');
        }

        $newTemplate = clone($template);
        $newTemplate->subject = $this->computeText($newTemplate->subject, $data);
        $newTemplate->content = $this->computeText($newTemplate->content, $data);

        return $newTemplate;
    }

    private function computeText(string $text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $siteFromRepository = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationFromRepository = DestinationRepository::getInstance()->getById($quote->destinationId);

            //Replace quote:destination_link
            if (strpos($text, '[quote:destination_link]') !== false){
                $destination = DestinationRepository::getInstance()->getById($quote->destinationId);

                $text = str_replace('[quote:destination_link]', '', $text);
                if ($destination) {
                    $text = str_replace(
                        '[quote:destination_link]',
                        $siteFromRepository->url . '/' . $destination->countryName . '/quote/' . $quoteFromRepository->id,
                        $text
                    );
                }
            }

            //Replace quote:summary_html
            if (strpos($text, '[quote:summary_html]') !== false) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($quoteFromRepository),
                    $text
                );
            }

            //Replace quote:summary
            if (strpos($text, '[quote:summary]') !== false) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($quoteFromRepository),
                    $text
                );
            }

            //Replace quote:destination_name
            if (strpos($text, '[quote:destination_name]') !== false) {
                $text = str_replace('[quote:destination_name]',$destinationFromRepository->countryName,$text);
            }
        }


        /*
         * USER
         * [user:*]
         */
        $user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if ($user) {
            //Replace user:first_name
            if (strpos($text, '[user:first_name]') !== false) {
                $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);
            }
        }

        return $text;
    }
}
