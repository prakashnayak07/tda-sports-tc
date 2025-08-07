<?php

namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Links extends AbstractHelper
{
    public function __invoke()
    {
        $view = $this->getView();
        $html = '';

        $backHref = $view->placeholder('back-href')->getValue();
        $backTitle = $view->placeholder('back-title')->getValue();
        $links = $view->placeholder('links')->getValue();

        if ($backHref || $links) {
            $html .= '<nav class="bg-white border-b border-gray-200 shadow-sm">';
            $html .= '<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 print:hidden">';
            $html .= '<div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">';

            // Back Link as Button
            if ($backHref && $backTitle) {
                $html .= '<div class="flex justify-center sm:justify-start">';
                $html .= sprintf(
                    '<a href="%s" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">',
                    htmlspecialchars($backHref, ENT_QUOTES, 'UTF-8')
                );
                $html .= '<svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>';
                $html .= '</svg>';
                $html .= '<span>' . htmlspecialchars($view->translate($backTitle), ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '</a>';
                $html .= '</div>';
            }

            // Related Links + Label in one line
            if ($links && is_array($links) && !empty($links)) {
                $html .= '<div class="flex flex-col sm:flex-row sm:items-center gap-3">';
                $html .= '<span class="text-sm text-gray-600 font-medium text-center sm:text-left">' . htmlspecialchars($view->translate('Related pages'), ENT_QUOTES, 'UTF-8') . ':</span>';

                $html .= '<div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">';
                foreach ($links as $title => $href) {
                    if (!empty($title) && !empty($href)) {
                        $html .= sprintf(
                            '<a href="%s" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 min-w-0">',
                            htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
                        );
                        $html .= '<span class="truncate">' . htmlspecialchars($view->translate($title), ENT_QUOTES, 'UTF-8') . '</span>';
                        $html .= '</a>';
                    }
                }
                $html .= '</div>';

                $html .= '</div>';
            }

            $html .= '</div>'; // end flex container
            $html .= '</div>'; // end container
            $html .= '</nav>';
        }

        return $html;
    }
}
