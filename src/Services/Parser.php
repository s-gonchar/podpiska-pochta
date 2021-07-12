<?php


namespace Services;


use dto\ProductDto;
use Entities\Agency;
use Entities\Magazine;
use Entities\Product;
use Entities\Region;
use Entities\Shop;
use Entities\Theme;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use PHPHtmlParser\Dom;
use Repositories\AgencyRepository;
use Repositories\LogRepository;
use Repositories\MagazineRepository;
use Repositories\RegionRepository;
use Repositories\ThemeRepository;

class Parser
{
    private const URI_THEME = 'https://podpiska.pochta.ru/theme';
    private const BASE_URI = 'https://podpiska.pochta.ru/';
    private const DOMAIN = 'podpiska.pochta.ru';

    private Client $client;
    private LogRepository $logRepository;
    private MagazineRepository $magazineRepository;
    private LogService $logService;
    private ThemeRepository $themeRepository;

    public function __construct(
        LogService $logService,
        LogRepository $logRepository,
        ThemeRepository $themeRepository,
        MagazineRepository $magazineRepository
    ) {
        $jar = new SessionCookieJar('PHPSESSID', true);
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'cookies' => $jar,
        ]);
        $this->logRepository = $logRepository;
        $this->magazineRepository = $magazineRepository;
        $this->logService = $logService;
        $this->themeRepository = $themeRepository;
    }

    /**
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function parseThemes()
    {
        $html = $this->getHtmlThemes();
        preg_match('/themes: JSON\.parse\(([^)]+)/', $html, $matches);
        $themes=json_decode(stripcslashes(trim($matches[1],'"')));
        foreach ($themes as $theme) {
            $theme = $this->themeRepository->findOneByExternalId($theme->id)
                ?: Theme::create($theme->title, $theme->id);
            $this->themeRepository->persist($theme);
        }

        $this->themeRepository->flush();
    }

    public function parse($themeExternalId = null)
    {
        try {
            $log = $this->logRepository->findLastOneSinceDt(new \DateTime('yesterday'));
            if ($log && $log->isSuccess()) {
                return;
            }

            $this->parseThemes();
            $themes = $themeExternalId ? [$this->themeRepository->getByExternalId($themeExternalId)]
                : $this->themeRepository->getAll();
            foreach ($themes as $theme) {
                $this->parseMagazinesByTheme($theme);
            }
            $this->logService->log();
        } catch (\Throwable $e) {
            $this->logService->log($e);
        }
    }


    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPHtmlParser\Exceptions\UnknownChildTypeException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \Exception
     */
    private function parseMagazinesByTheme(Theme $theme)
    {
        $response = $this->client->request('GET', self::URI_THEME,
                    ['query' => "p_p_id=themes_WAR_onlinesubscriptionportlet&p_p_lifecycle=2&p_p_resource_id=nextPage&idOrAlias={$theme->getExternalId()}&fromElement=0&elementsCount=1000"]
                );
                $responseData = json_decode($response->getBody(), true);
        foreach ($responseData['data'] as $magazineData) {
            $publicationCode = $magazineData['publicationCode'] ?? null;
            if (!$magazine = $this->magazineRepository->findOneByPublicationCode($publicationCode)) {
//                $response = $this->client->request('GET', self::BASE_URI,
//                    ['query' => 'p_p_lifecycle=2&p_p_mode=view&p_p_resource_id=publication&p_p_id=restapi_WAR_onlinesubscriptionportlet&publicationCode=' . $publicationCode]
//                );
//                $responseData = json_decode($response->getBody(), true);
                $magazine = Magazine::create($magazineData);
                $magazine->getThemes()->add($theme);
            } else {
                if (!$magazine->getThemes()->contains($theme)) {
                    $magazine->getThemes()->add($theme);
                }
            }
            $this->magazineRepository->persist($magazine);
        }

        $this->themeRepository->flush();
    }

    private function getHtmlThemes(): bool|string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://podpiska.pochta.ru/theme');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Sec-Ch-Ua: \" Not;A Brand\";v=\"99\", \"Google Chrome\";v=\"91\", \"Chromium\";v=\"91\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Sec-Fetch-Site: none';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-User: ?1';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cookie: ANALYTICS_UUID=36e40f8f-b896-4fdc-967e-686bc4292b86; _ga=GA1.2.834815054.1619892196; ANON_CART_ID=9d2fc1a82996466dbc4874827cdb11f9; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; COOKIE_SUPPORT=true; GUEST_LANGUAGE_ID=en_US; _ym_uid=1625819835354982329; _ym_d=1625819835; _gid=GA1.2.1853349476.1626043588; RP.SID=c2f5b48d32ce4a97a98a8829b5292dc0; JSESSIONID=9BB43F01460165E7D065AEB5E27905A9-n2; XDEBUG_SESSION=XDEBUG_ECLIPSE; _gat=1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }
}