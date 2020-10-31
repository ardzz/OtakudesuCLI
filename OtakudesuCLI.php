#!/usr/bin/php
<?php

/**
 * OtakudesuCLI
 * 
 * @author Ardan <ardzz@indoxploit.or.id>
 * @package Library
 * @copyright Otakudesu
 */

require __DIR__ . "/vendor/autoload.php";

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use jc21\CliTable;
use jc21\CliTableManipulator;
use Otakudesu\Config\Config;
use Otakudesu\Page\Fetch as Otakudesu;

class OtakudesuCLI extends CLI{

    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options) : void {
        $options->setHelp("Unofficial Otakudesu Client (CLI based) mempermudah kepentingan nge-weaaboo di semua OS!");
        $options->registerOption("install", "Install OtakudesuCLI ke sistem", "p");
        $options->registerOption("uninstall", "Unstall OtakudesuCLI dari sistem", "u");
        $options->registerOption("anime-list", "Lihat daftar anime yang tersedia di Otakudesu", "l");
        $options->registerOption("complete-anime", "Lihat daftar anime (lengkap)", "c");
        $options->registerOption("ongoing-anime", "Lihat daftar anime (ongoing)", "o");
        $options->registerOption("genre-list", "Lihat daftar genre anime", "g");
        $options->registerOption("cari-anime", "Cari anime", "s", "nama_anime");
        $options->registerOption("info-anime", "Cari informasi anime, contohnya : ogairu-season-3-sub-indo", "i", "id_anime");
        $options->registerArgument("argument", "Arguments can be required or optional. This one is optional", false);
        $options->registerOption("version", "Print current version", 'v');
    }

    /**
     * OtakudesuCLI main program
     *
     * Arguments and options have been parsed when this is run
     *
     * @param Options $options
     * @return void
     */
    protected function main(Options $options) : void {

        $Otakudesu = new otakudesu;

        if ($options->getOpt("install")) {
            if($Otakudesu->checkOS() === "windows") {
                $this->info("Maaf, tidak bisa menggunakan fitur ini di windows.");
                die;
            }
            if ($Otakudesu->isRoot()) {
                $this->info("Membuat pintasan ke /usr/bin/otakudesu");
                $target = "/usr/bin/otakudesu";
                if (!is_file($target)) {
                    if (symlink(__FILE__, $target)) {
                        chmod($target, 0755);
                        $this->success("OtakudesuCLI berhasil dipasang di sistem!");
                        $this->info("Gunakan perintah 'otakudesu' untuk menjalankan OtakudesuCLI");
                    }else{
                        $this->error("Gagal memasang OtakudesuCLI");
                    }
                } else {
                    $this->error("OtakudesuCLI telah terpasang di perangkat kamu!");
                }          
            } else {
                $this->error("Untuk memasang OtakudesuCLI harus menggunakan user root!");
                $this->info("Gunakan perintah 'sudo " . basename(__FILE__) . " -i' TANPA TANDA KUTIP!");
            }    
        }
        elseif ($options->getOpt("uninstall")) {
            if($Otakudesu->checkOS() === "windows") {
                $this->info("Maaf, tidak bisa menggunakan fitur ini di windows.");
                die;
            }
            if ($Otakudesu->isRoot()) {
                $this->info("Menghapus pintasan ke /usr/bin/otakudesu");
                $target = "/usr/bin/otakudesu";
                if (is_link($target)) {
                    if (unlink($target)) {
                        $this->success("OtakudesuCLI berhasil dihapus di sistem!");
                    }else{
                        $this->error("Gagal meng-uninstall OtakudesuCLI");
                    }
                } else {
                    $this->error("OtakudesuCLI tidak terpasang di perangkat kamu!");
                }          
            } else {
                $this->error("Untuk meng-uninstall OtakudesuCLI harus menggunakan user root!");
                $this->info("Gunakan perintah 'sudo " . basename(__FILE__) . " -u' TANPA TANDA KUTIP!");
            }    
        }
        elseif ($options->getOpt("anime-list")){
            $data  = $Otakudesu->getAnimeList();
            $table = new CliTable;
            $table->setTableColor("green");
            $table->setHeaderColor("yellow");
            $table->addField("No", "no", false, "white");
            $table->addField("Judul Anime", "title", false, "white");
            $table->addField("Id Anime", "id", false, "white");
            $table->injectData($data);
            $table->display();
        }
        elseif ($options->getOpt("complete-anime")) {
            $data  = $Otakudesu->getCompliteAnime();
            $table = new CliTable;
            $table->setTableColor("green");
            $table->setHeaderColor("yellow");
            $table->addField("Judul Anime", "title", false, "white");
            $table->addField("Rating", "star", false, "white");
            $table->addField("Id Anime", "id", false, "white");
            $table->injectData($data);
            $table->display();
        }
        elseif ($options->getOpt("ongoing-anime")) {
            $data  = $Otakudesu->getOngoingAnime();
            $table = new CliTable;
            $table->setTableColor("green");
            $table->setHeaderColor("yellow");
            $table->addField("Judul Anime", "title", false, "white");
            $table->addField("Episode", "episode", false, "white");
            $table->addField("Hari", "hari", false, "white");
            $table->addField("Id Anime", "id", false, "white");
            $table->injectData($data);
            $table->display();
        }
        elseif ($options->getOpt("genre-list")) {
            $data  = $Otakudesu->getGenreList();
            $table = new CliTable;
            $table->setTableColor("green");
            $table->setHeaderColor("yellow");
            $table->addField("No", "no", false, "white");
            $table->addField("Genre", "genre", false, "white");
            $table->injectData($data);
            $table->display();
        }
        elseif ($options->getOpt("cari-anime")) {
            $data = $Otakudesu->searchAnime($options->getOpt("cari-anime"));
            if ($data) {
                $table = new CliTable;
                $table->setTableColor("green");
                $table->setHeaderColor("yellow");
                $table->addField("Judul", "title", false, "white");
                $table->addField("Rating", "rating", false, "white");
                $table->addField("Status", "status", false, "white");
                $table->addField("Id", "id", false, "white");
                $table->injectData($data);
                $table->display();
            } else {
                $this->alerT("Pencarian tidak ditemukan");
            }
            
        }
        elseif ($options->getOpt("info-anime")) {
            $data = $Otakudesu->getAnimeInfo($options->getOpt("info-anime"));
            if ($data) {
                $anime_info = [];
                foreach ($data["anime_info"] as $key => $value) {
                    $anime_info[] = [
                        "1" => ucfirst($key),
                        "2" => $value
                    ];
                }

                $table = new CliTable;
                $table->setTableColor("green");
                $table->setHeaderColor("yellow");
                $table->addField("Variabel", "1", false, "white");
                $table->addField("Nilai", "2", false, "white");
                $table->injectData($anime_info);
                $table->display();

                foreach ($data["download"]["episode"] as $key => $value) {
                    $download = [];
                    $judul = $value["title"];
                    foreach ($value["quality"] as $key => $value) {
                        foreach ($value as $key => $value) {
                            $download[] = $value;
                        }
                    }
                    $table = new CliTable;
                    $table->setTableColor("green");
                    $table->setHeaderColor("yellow");
                    $table->addField("Resolusi", "quality", false, "white");
                    $table->addField("Server", "server", false, "white");
                    $table->addField("Size", "size", false, "white");
                    $table->addField("Link ({$judul})", "link", false, "white");
                    $table->injectData($download);
                    $table->display();
                }

                $download = [];
                foreach ($data["download"]["batch"] as $key => $value) {
                    foreach ($value as $key => $value) {
                        $download[] = $value;
                    }
                }

                $table = new CliTable;
                $table->setTableColor("green");
                $table->setHeaderColor("yellow");
                $table->addField("Resolusi", "quality", false, "white");
                $table->addField("Server", "server", false, "white");
                $table->addField("Size", "size", false, "white");
                $table->addField("Link (Batch)", "link", false, "white");
                $table->injectData($download);
                $table->display();

            } else {
                $this->alert("Id anime tidak ditemukan");
            }
            
        }
        elseif ($options->getOpt('version')) {
            $this->info("Saat ini kamu menggunakan OtakudeseCLI versi : " . Config::OtakudesuCLI_Version);
        }
        else{
            echo $options->help();
        }
    }
}

(new OtakudesuCLI)->run();
