<?php

    use YoutubeDl\YoutubeDl;
    use YoutubeDl\Exception\CopyrightException;
    use YoutubeDl\Exception\NotFoundException;
    use YoutubeDl\Exception\PrivateVideoException;

function GetTwitterVideo($videoUrl){
    $dl = new YoutubeDl([
    'continue' => true, // force resume of partially downloaded files. By default, youtube-dl will resume downloads if possible.
    //'format' => 'bestvideo',
    'restrict-filenames' => true,
    ]);
    $dl->setDownloadPath(VIDEODIR);

    try {
        $video = $dl->download($videoUrl);
        $newfile = VIDEODIR . $video->getFilename();
        if(file_exists($newfile)) return $newfile;
        return  false;
        //return $video;
        // $video->getFile(); // \SplFileInfo instance of downloaded file
    } catch (NotFoundException $e) {
        // Video not found
        echo "<h1>Error: Video not found</h1>";
        return false;
    } catch (PrivateVideoException $e) {
        echo "<h1>Error: Video is private</h1>";
        return false;
        // Video is private
    } catch (CopyrightException $e) {
        echo "<h1>Error: Copyright</h1>";
        return false;
        // The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement
    } catch (\Exception $e) {
        // Failed to download
        echo "<h1>Download failed</h1>" . print_r($e,true);
        return false;
    }    
        
    
}    
    