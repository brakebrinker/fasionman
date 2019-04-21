<?php
/**
 * The Star Rating class
 *
 * @package star_rating
 */
class starRating extends xPDOSimpleObject {
    public $vote = null;
    public $startDate = null;
    public $endDate = null;
    public $config = array();
    public $output = '';

    /**
     * Initialize default config settings
     */
    public function initialize() {
        $this->config = array(
            'useSession' => false,
            'sessionName' => 'starrating',
            'useCookie' => false,
            'cookieName' => 'starrating',
            'cookieExpiry' => 608400,
            'theme' => 'default',
            'maxStars' => 5,
            'imgWidth' => 25,
            'cssFile' => 'star',
            'urlPrefix' => '',
            'starTpl' => 'starTpl',
        );
    }

    /**
     * Setup custom config variables
     *
     * @param array $array An array of configuration properties
     */
    public function setConfig($config = array()) {
        $this->config = array_merge($this->config,$config);
    }

    /**
     * Render the star rating
     */
    public function renderVote() {
        if (($this->startDate == null || date('Y-m-d H:i:s',time()) > $this->startDate) && ($this->endDate == null || date('Y-m-d H:i:s', time()) < $this->endDate)) {
            $voteAllowed = $this->allowVote();
            $voteStats = $this->getVoteStats();
            /* TODO: replace with lexicon */
            $currentText = round($voteStats['average'].'/'.$this->config['maxStars'], 2);
            $listItems = '<ul class="star-rating-'.$this->config['theme'].'" style="width:'.$this->config['maxStars'] * $this->config['imgWidth'].'px">';
            for($i = 0; $i <= $this->config['maxStars']; $i++) {
                if ($i == 0) {
                    $listItems .= '<li class="current-rating" style="width:'. $voteStats['percentage']. '%;">'.$currentText.'</li>';
                } else {
                    $starWidth = floor(100 / $this->config['maxStars'] * $i);
                    $starIndex = ($this->config['maxStars'] - $i) + 2;
                    if ($voteAllowed) {
                        $prefix = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                        if (!empty($this->config['urlPrefix'])) {
                            $prefix = $this->config['urlPrefix'].'&'.$prefix;
                        }
                        $urlParams = $prefix . '&vote='.$i.'&amp;star_id=' . $this->get('star_id');
                        $listItems .= '<li class="star"><a href="'. $this->xpdo->makeUrl($this->xpdo->resource->get('id'), '', $urlParams) . '" title="' . $i . '/' . $this->config['maxStars'] . '" style="width:'.$starWidth .'%;z-index:'.$starIndex . '" rel="nofollow">'. $i . '</a></li>';
                    } else {
                        $listItems .= '<li class="star"><span>'. $i .'</span></li>';
                    }
                }
            }
            $listItems .= '</ul>';
        }
        $ph['rating'] = $listItems;
        $ph = array_merge($ph, $voteStats);
        $chunk = $this->xpdo->getObject('modChunk',array(
            'name' => $this->config['starTpl'],
        ));
        if ($chunk) {
            $this->output = $chunk->getContent();
        } else {
            $this->output = '[[+rating]]<span class="totalvotes">Votes: [[+vote_count]]</span>';
        }
        foreach ($ph as $key => $value) {
            $this->output = str_replace('[[+' . $key . ']]', $value, $this->output);
        }
        return $this->output;
    }

    /**
     * Returns an array of the current vote stats
     *
     * @return array
     */
    public function getVoteStats() {
        $row = $this->toArray();
        $row['average'] = ($row['vote_count'] != 0) ? $row['vote_total'] / $row['vote_count'] : 0;
        $row['percentage'] = floor(($row['average']/$this->config['maxStars'])*100);
        return $row;
    }

    /**
     * Add vote if necessary permission and validation checks are okay
     *
     * @return boolean
     */
    public function addVote() {
        $added = false;
        if (is_numeric($this->vote) && $this->allowVote() && $this->validateVote()) {
            $vt = $this->get('vote_total') + $this->vote;
            $vc = $this->get('vote_count') + 1;

            $this->set('vote_total', $vt);
            $this->set('vote_count', $vc);
            if ($this->save()) {
                $this->setVoteRestrictions();
                $added = true;
            }
        }
        return false;
    }

    /**
     * Set the vote to a certain value
     *
     * @param integer $vote
     */
    public function setVote($vote) {
        $this->vote = $vote;
    }

    /**
     * Check vote is within limits of the star rating
     *
     * @return boolean
     */
    public function validateVote() {
        if (!empty($this->vote) && intval($this->vote) > 0 && intval($this->vote) <= $this->config['maxStars']) {
            return true;
        }
        return false;
    }

    /**
     * Check that the vote is allowed (cookie, session, user permissions)
     *
     * @return boolean
     */
    public function allowVote() {
        $uniqueId = ($this->get('group_id') != '' ? $this->get('group_id').'-' : '') . $this->get('star_id');
        /* session */
        if ($this->config['useSession'] == true
            && array_key_exists($this->config['sessionName'],$_SESSION)) {
            $sessionArray = $_SESSION[$this->config['sessionName']];
            if (!empty($sessionArray) && in_array($uniqueId, $sessionArray)) {
                return false;
            }
        }
        /* cookie */
        if ($this->config['useCookie'] == true) {
            if (isset ($_COOKIE[$this->config['cookieName'] . $uniqueId])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Prevent user from voting twice
     *
     * @return boolean
     */
    public function setVoteRestrictions() {
        $uniqueId = ($this->get('group_id') != '' ? $this->get('group_id').'-' : '') . $this->get('star_id');
        /* session */
        if ($this->config['useSession'] == true) {
            if (!array_key_exists($this->config['sessionName'], $_SESSION)) $_SESSION[$this->config['sessionName']] = array();
            if (!in_array($uniqueId, $_SESSION[$this->config['sessionName']])) {
                array_push($_SESSION[$this->config['sessionName']], $uniqueId);
            }
        }

        /* cookie */
        if ($this->config['useCookie'] == true) {
            setcookie($this->config['cookieName'] . $uniqueId, 'novote', time() + $this->config['cookieExpiry']);
        }
        return true;
    }

    /**
     * Load custom theme
     *
     * @return boolean
     */
    public function loadTheme() {
        $loaded = false;
        $f = $this->xpdo->getOption('base_path').'assets/components/star_rating/themes/'.$this->config['theme'].'/'.$this->config['cssFile'].'.css';

        if (file_exists($f)) {
            $url = $this->xpdo->getOption('base_url') . 'assets/components/star_rating/themes/' . $this->config['theme'] . '/' . $this->config['cssFile'] . '.css';
            $this->xpdo->regClientCSS($url);
            $loaded = true;
        }
        return $loaded;
    }

    public function getRedirectUrl() {

        $qs = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $qsCleansed = array();
        if (strlen($qs) > 0) {
            $qsParts = explode('&', $qs);
            foreach ($qsParts as $key => $part) {
                $param = explode('=', $part);
                if (!in_array($param[0], array('vote', 'star_id', 'group_id'))) {
                    $qsCleansed[] = $param[0] . '=' . $param[1];
                }
            }
        }
        return $this->xpdo->makeUrl($this->xpdo->resource->get('id'), '', implode('&', $qsCleansed));
    }
}