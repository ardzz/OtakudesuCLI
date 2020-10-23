<?php

/**
 * OtakudesuCLI
 * 
 * @author Ardan <ardzz@indoxploit.or.id>
 * @package Library
 * @copyright Otakudesu
 */

namespace Otakudesu\Page;

use Otakudesu\Requests\GET;
use Otakudesu\Config\Config;
use voku\helper\HtmlDomParser;

class Fetch{

    function isRoot(){
        return posix_getuid() == 0;
    }

    function getString($str, $find_start, $find_end) {
		$start = @strpos($str, $find_start);
		if (!$start) {
            return false;
		}
		$length = strlen($find_start);
		$end    = strpos(substr($str, $start + $length), $find_end);
		return trim(substr($str, $start + $length, $end));
	}
    
    function getCompliteAnime($page = false){
        $request = new GET;
        if ($page && is_numeric($page)) {
            $body    = $request->Run("complete-anime/page/{$page}/");
            if (stripos($body, "not found")) {
                return false;
            } else {
                $dom = HtmlDomParser::str_get_html($body); 
                $output = [];
                $X = 0;
                foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser div.rseries div.rapi div.venz div.detpost") as $key => $value) {
                    foreach ($value->find("div.epztipe") as $key_star => $value_star) {
                        $output[$X]["star"] = $value_star->plaintext;
                    }

                    foreach ($value->find("div.newnime") as $key_newnime => $value_newnime) {
                        $output[$X]["released"] = $value_newnime->plaintext;
                    }

                    foreach ($value->find("div.thumb") as $key_thumb => $value_thumb) {
                        foreach ($value_thumb->find("a") as $key_thumb_a => $value_thumb_a) {
                            $output[$X]["url"] = $value_thumb_a->href;
                            $output[$X]["id"] = str_replace([Config::Otakudesu_URL . "/anime/", "/"], "", $value_thumb_a->href);
                            //$output[$X]["title"] = html_entity_decode($value_thumb_a->title);
                        }

                        foreach ($value_thumb->find("h2") as $key_h2 => $value_h2) {
                            $output[$X]["title"] = html_entity_decode($value_h2->plaintext);
                        }


                        foreach ($value_thumb->find("img") as $key_thumb_img => $value_thumb_img) {
                            $output[$X]["thumbnail"] = $value_thumb_img->src;
                        }
                    }

                    $X++;
                }
                return $output;
            }
        }else{
            $body    = $request->Run("complete-anime/");
            if (stripos($body, "not found")) {
                return false;
            } else {
                $dom = HtmlDomParser::str_get_html($body); 
                $output = [];
                $X = 0;
                foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser div.rseries div.rapi div.venz div.detpost") as $key => $value) {
                    foreach ($value->find("div.epztipe") as $key_star => $value_star) {
                        $output[$X]["star"] = $value_star->plaintext;
                    }

                    foreach ($value->find("div.newnime") as $key_newnime => $value_newnime) {
                        $output[$X]["released"] = $value_newnime->plaintext;
                    }

                    foreach ($value->find("div.thumb") as $key_thumb => $value_thumb) {
                        foreach ($value_thumb->find("a") as $key_thumb_a => $value_thumb_a) {
                            $output[$X]["url"] = $value_thumb_a->href;
                            $output[$X]["id"] = str_replace([Config::Otakudesu_URL . "/anime/", "/"], "", $value_thumb_a->href);
                            //$output[$X]["title"] = html_entity_decode($value_thumb_a->title);
                        }

                        foreach ($value_thumb->find("h2") as $key_h2 => $value_h2) {
                            $output[$X]["title"] = html_entity_decode($value_h2->plaintext);
                        }

                        foreach ($value_thumb->find("img") as $key_thumb_img => $value_thumb_img) {
                            $output[$X]["thumbnail"] = $value_thumb_img->src;
                        }
                    }

                    $X++;
                }
                return $output;
            }
        }
    }

    function getAnimeList(){
        $request = new GET;
        $body    = $request->Run("anime-list/");
        $dom     = HtmlDomParser::str_get_html($body);
        $X       = 0;

        foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser div.daftarkartun div[id=abtext] div.penzbar") as $key => $value) {
            foreach ($value->find("a") as $key => $value) {
                $output[$X]["no"] = $X;
                $output[$X]["title"] = html_entity_decode($value->plaintext);
                $output[$X]["id"] = str_replace([Config::Otakudesu_URL . "/anime/", "/"], "", $value->href);
            }
            $X++;
        }

        return $output;
    }

    function getOngoingAnime(){
        $request = new GET;
        $body    = $request->Run("ongoing-anime/");
        $dom     = HtmlDomParser::str_get_html($body); 
        $output  = [];
        $X       = 0;

        foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser div.venutama div.rseries div.rapi div.venz div.detpost") as $key => $value) {
            foreach ($value->find("div.epz") as $key_epz => $value_epz) {
                $output[$X]["episode"] = $value_epz->plaintext;
            }

            foreach ($value->find("div.epztipe") as $key_epztipe => $value_epztipe) {
                $output[$X]["hari"] = $value_epztipe->plaintext;
            }

            foreach ($value->find("h2.jdlflm") as $key_jdlflm => $value_jdlflm) {
                $output[$X]["title"] = html_entity_decode($value_jdlflm->plaintext);
            }

            foreach ($value->find("a") as $key_a => $value_a) {
                $output[$X]["url"] = $value_a->href;
                $output[$X]["id"] = str_replace([Config::Otakudesu_URL . "/anime/", "/"], "", $value_a->href);
            }

            foreach ($value->find("img") as $key_img => $value_img) {
                $output[$X]["thumbnail"] = $value_img->src;
            }

            $X++;
        }
        return $output;
    }

    function getGenreList(){
        $request = new GET;
        $body    = $request->Run("genre-list/");
        $dom     = HtmlDomParser::str_get_html($body); 
        $output  = [];
        $X       = 0;

        foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser ul.genres") as $key => $value) {
            foreach ($value->find("a") as $key_star => $value_star) {
                $output[$X]["no"]    = $X+1;
                $output[$X]["genre"] = $value_star->plaintext;
                $X++;
            }
        }
        return $output;
    }

    function searchAnime($query){
        $request = new GET;
        $body    = $request->Run("?s={$query}&post_type=anime");
        $dom     = HtmlDomParser::str_get_html($body); 
        $output  = [];
        $X       = 0;

        foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser div.venutama div.page ul.chivsrc li") as $key => $value) {
            foreach ($value->find("img") as $key_img => $value_img) {
                $output[$X]["thumbnail"] = $value_img->src;
                $output[$X]["title"] = $value_img->alt;
            }
            foreach ($value->find("h2") as $key_h2 => $value_h2) {
                //$output[$X]["title"] = html_entity_decode($value_h2->plaintext);
                $temp = [];
                foreach ($value->find("a") as $key_a => $value_a) {
                    $temp[] = $value_a;
                }
                $output[$X]["url"] = $temp[0]->href;
                $output[$X]["id"]  = str_replace([Config::Otakudesu_URL . "/anime/", "/"], "", $temp[0]->href);
                unset($temp[0]);
                foreach ($temp as $key_temp => $value_temp) {
                    $output[$X]["categories"][] = $value_temp->plaintext;
                }
            }
            $temp = [];
            foreach ($value->find("div.set") as $key_set => $value_set) {
                $temp[] = $value_set->plaintext;
            }
            $output[$X]["rating"] = str_replace("Rating : ", "", $temp[2]);
            $output[$X]["status"] = strtolower(str_replace("Status : ", "", $temp[1]));
            $output[$X]["categories_string"] = join(" and ", array_filter(array_merge(array(join(", ", array_slice($output[$X]["categories"], 0, -1))), array_slice($output[$X]["categories"], -1)), "strlen"));
            $X++;
        }

        return $output;
    }

    function getAnimeInfo($id){
        $request = new GET;
        $first   = $request->Run("anime/{$id}/");
        //return $first;
        if (!$first) {
            return false;
        }
        $body    = $request->Run("lengkap/" . $this->getString($first, Config::Otakudesu_URL . "/lengkap/", "/") . "/");
        $dom     = HtmlDomParser::str_get_html($body); 
        $output  = [];
        $X       = 0;

        foreach ($dom->find("div.wowmaskot div[id=venkonten] div.vezone div.venser") as $key => $value) {
            foreach ($value->find("div.jdlrx") as $key_jdlrx => $value_jdlrx) {
                $output["title"] = html_entity_decode($value_jdlrx->plaintext);
            }
            foreach ($value->find("div.imganime") as $key_imganime => $value_imganime) {
                foreach ($value_imganime->find("img") as $key_img => $value_img) {
                    $output["thumbnail"] = $value_img->src;
                }
            }
            foreach ($value->find("div.infos") as $key_infos => $value_infos) {
                $temp = $value_infos->outertext;
                $temp = str_replace("</b>", "", $temp);
                $temp = str_replace("<br>", "</b>", $temp);
                $anime_info = HtmlDomParser::str_get_html($temp);

                foreach ($anime_info->find("b") as $key_info => $value_info) {
                    $info = explode(": ", $value_info->plaintext);
                    $output["anime_info"][strtolower($info[0])] = $info[1];
                }
            }
            $sinopsis = [];
            foreach ($value->find("div.deskripsi div div p") as $key_deskripsi => $value_deskripsi) {
                $sinopsis[] = $value_deskripsi->plaintext;
            }
            $output["sinopsis"] = $sinopsis;

            $download = [];
            foreach ($value->find("div.download") as $key_download => $value_download) {
                $download[] = $value_download;
            }
            $episode = 0;
            foreach ($download[0]->find("h4") as $key_ul => $value_ul) {
                $output["download"]["episode"][$episode]["title"] = ($value_ul->plaintext);
                $episode++;
            }
            $episode = 0;
            foreach ($download[0]->find("ul") as $key_ul => $value_ul) {
                $all_links = [];
                foreach ($value_ul->find("li") as $key_li => $value_li) {
                    foreach ($value_li->find("strong") as $key_strong => $value_strong) {
                        $quality = $value_strong->plaintext;
                    }
                    $link = [];
                    $xx = 0;
                    foreach ($value_li->find("i") as $key_i => $value_i) {
                        $size = $value_i->plaintext;
                    }
                    foreach ($value_li->find("a") as $key_a => $value_a) {
                        $link[] = [
                            "quality" => $quality,
                            "server" => $value_a->plaintext,
                            "link" => $value_a->href,
                            "size" => $size
                        ]; 
                    }
                    $xx++;
                    $all_links[] = $link;
                }
                $output["download"]["episode"][$episode]["quality"] = $all_links;
                $episode++;
            }

            foreach ($download[1]->find("ul") as $key_ul => $value_ul) {
                $all_links = [];
                foreach ($value_ul->find("li") as $key_li => $value_li) {
                    foreach ($value_li->find("strong") as $key_strong => $value_strong) {
                        $quality = $value_strong->plaintext;
                    }
                    $link = [];
                    $xx = 0;
                    foreach ($value_li->find("i") as $key_i => $value_i) {
                        $size = $value_i->plaintext;
                    }
                    foreach ($value_li->find("a") as $key_a => $value_a) {
                        $link[] = [
                            "quality" => $quality,
                            "server" => $value_a->plaintext,
                            "link" => $value_a->href,
                            "size" => $size
                        ]; 
                    }
                    $xx++;
                    $all_links[] = $link;
                }
                $output["download"]["batch"] = $all_links;
                $episode++;
            }
        }

        return $output;
    }
}