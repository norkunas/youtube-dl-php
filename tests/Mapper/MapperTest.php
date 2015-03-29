<?php
namespace YoutubeDl\Tests\Mapper;

use YoutubeDl\Mapper\Mapper;
/**
 * @covers YoutubeDl\Mapper\Mapper
 */
class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $data = [
            'upload_date' => '20061029',
            'extractor' => 'youtube',
            'height' => 480,
            'fulltitle' => 'Eminem - MockingBird',
            'playlist_index' => 1,
            'view_count' => 18704931,
            '_filename' => 'Eminem - MockingBird-4jwYHn0iwgI.m4a',
            'dislike_count' => 1837,
            'width' => 640,
            'subtitles' => [
                'en' => '1
00:00:01,000 --> 00:00:06,000
Every day throw away millions of electronic devices, because they get old and are worn out.

2
00:00:07,300 --> 00:00:09,000
But usually it is only one of the components that causes the problem.

3
00:00:12,000 --> 00:00:15,000
The rest of the device works fine, but is needesly thrown away.',
            ],
            'age_limit' => 18,
            'annotations' => '<?xml version="1.0" encoding="UTF-8" ?><document><annotations>
<annotation id="annotation_291910" type="text" style="highlightText" logable="false">
  <TEXT>CLICK HERE FOR MORE!</TEXT>
  <segment spaceRelative="annotation_410701">
    <movingRegion type="rect">
      <rectRegion x="61.87500" y="32.77800" w="22.08300" h="29.16700" t="never"/>
      <rectRegion x="61.87500" y="32.77800" w="22.08300" h="29.16700" t="never"/>
    </movingRegion>
  </segment>
  <appearance bgAlpha="0" textSize="3.6" highlightFontColor="13369344"/>
  <trigger>
    <condition ref="annotation_410701" state="rollOver"/>
  </trigger>
</annotation>

<annotation id="annotation_410701" type="highlight" log_data="a-type=3&amp;link=http%3A%2F%2Fwww.youtube.com%2Fuser%2FAndrewvlogs&amp;l-class=3&amp;xble=1&amp;len-sec=303&amp;a-id=annotation_410701">
  <segment>
    <movingRegion type="rect">
      <rectRegion x="0.00000" y="1.11100" w="100.00000" h="96.38900" t="0:00:00.6"/>
      <rectRegion x="0.00000" y="1.11100" w="100.00000" h="96.38900" t="0:05:03.8"/>
    </movingRegion>
  </segment>
  <appearance bgColor="13369344" highlightWidth="3" borderAlpha="0.25"/>

  <action type="openUrl" trigger="click">
    <url target="new" value="https://www.youtube.com/user/Andrewvlogs" link_class="3"/>
  </action>

</annotation>
</annotations></document>',
            'acodec' => 'aac',
            'display_id' => '4jwYHn0iwgI',
            'format' => '141 - audio only (DASH audio)',
            'tbr' => 255,
            'preference' => -50,
            'uploader' => 'crazyEMINEMfan',
            'uploader_id' => 'crazyEMINEMfan',
            'categories' => [
                'Music'
            ],
            'stitle' => 'Eminem - MockingBird',
            'thumbnails' => [
                [
                    'id' => '0',
                    'url' => 'https://i.ytimg.com/vi/4jwYHn0iwgI/hqdefault.jpg',
                ],
            ],
            'extractor_key' => 'Youtube',
            'vcodec' => 'VP9',
            'webpage_url' => 'https://www.youtube.com/watch?v=4jwYHn0iwgI',
            'formats' => [
                [
                    'format' => 'nondash-171 - audio only (DASH audio)',
                    'url' => 'https://r3---sn-h8u8-30oe.googlevideo.com/videoplayback?requiressl=yes&clen=3225626&sver=3&signature=DBA94B3C6FDC24F0233E9A42F6D43D160E88941A.F9CC5DEC3417B3493DFF14240F5C25C02441E3A2&mime=audio%2Fwebm&fexp=900720%2C907263%2C934954%2C9405136%2C9406616%2C9407103%2C9407796%2C9408092%2C9408101%2C948124%2C948703%2C951511%2C951703%2C952612%2C957201%2C961404%2C961406&ipbits=0&itag=171&upn=als6NALKK9g&gcr=lt&expire=1427574005&mt=1427552171&sparams=clen%2Cdur%2Cgcr%2Cgir%2Cid%2Cip%2Cipbits%2Citag%2Ckeepalive%2Clmt%2Cmime%2Cmm%2Cms%2Cmv%2Cpl%2Crequiressl%2Csource%2Cupn%2Cexpire&gir=yes&key=yt5&id=o-AFos8WoZ1AGGSzwYN1hyZfs3gWtpDQQprSYr5cpx74vs&mm=31&ms=au&keepalive=yes&mv=u&source=youtube&pl=27&dur=303.835&lmt=1397470820549546&ip=178.16.37.111&ratebypass=yes',
                    'vcodec' => 'none',
                    'format_note' => 'DASH audio',
                    'abr' => 128,
                    'player_url' => null,
                    'ext' => 'webm',
                    'preference' => -10050,
                    'format_id' => 'nondash-171',
                    'acodec' => 'aac',
                    'container' => 'm4a_dash',
                    'width' => 640,
                    'height' => 480,
                    'asr' => 44100,
                    'tbr' => 90,
                    'fps' => 15,
                    'filesize' => 2201477,
                ]
            ],
            'format_note' => '',
            'like_count' => '',
            'duration' => 304,
            'id' => '4jwYHn0iwgI',
            'container' => 'm4a_dash',
            'average_rating' => 4.73778676987,
            'abr' => 256,
            'fps' => 15,
            'webpage_url_basename' => 'watch',
            'asr' => 44100,
            'description' => 'Partner with NetCastTV and get a Sign-on Bonus! http://www.freedom.tm/NetCastTV
http://www.freedom.tm/NetCastTV',
            'filesize' => 9689720,
            'format_id' => '141',
            'url' => 'https://r3---sn-h8u8-30oe.googlevideo.com/videoplayback?id=e23c181e7d22c202&itag=141&source=youtube&requiressl=yes&gcr=lt&mm=31&ms=au&pl=27&mv=u&ratebypass=yes&mime=audio/mp4&gir=yes&clen=9689720&lmt=1389666151121609&dur=303.902&mt=1427552171&fexp=900720,907263,934954,9405136,9406616,9407103,9407796,9408092,9408101,948124,948703,951511,951703,952612,957201,961404,961406&signature=7E7800804F78B16CEE70E197AB167D7BC3BAF4F4.19E14590E715A53A35A4A38F62403AC842F11827&sver=3&upn=als6NALKK9g&key=dg_yt0&ip=178.16.37.111&ipbits=0&expire=1427574005&sparams=ip,ipbits,expire,id,itag,source,requiressl,gcr,mm,ms,pl,mv,ratebypass,mime,gir,clen,lmt,dur',
            'title' => 'Eminem - MockingBird',
            'ext' => 'm4a',
        ];

        $mapper = new Mapper();
        $object = $mapper->map($data);

        $this->assertInstanceOf('YoutubeDl\Entity\Video', $object);
        $this->assertInstanceOf('DateTime', $object->getUploadDate());

        $this->assertEquals($data['upload_date'], $object->getUploadDate()->format('Ymd'));
        $this->assertEquals($data['extractor'], $object->getExtractor());
        $this->assertEquals($data['height'], $object->getHeight());
        $this->assertEquals($data['fulltitle'], $object->getFulltitle());
        $this->assertEquals($data['playlist_index'], $object->getPlaylistIndex());
        $this->assertEquals($data['view_count'], $object->getViewCount());
        $this->assertEquals($data['_filename'], $object->getFilename());
        $this->assertEquals($data['dislike_count'], $object->getDislikeCount());
        $this->assertEquals($data['width'], $object->getWidth());
        $this->assertEquals($data['age_limit'], $object->getAgeLimit());
        $this->assertEquals($data['acodec'], $object->getAcodec());
        $this->assertEquals($data['display_id'], $object->getDisplayId());
        $this->assertEquals($data['format'], $object->getFormat());
        $this->assertEquals($data['tbr'], $object->getTbr());
        $this->assertEquals($data['preference'], $object->getPreference());
        $this->assertEquals($data['uploader'], $object->getUploader());
        $this->assertEquals($data['uploader_id'], $object->getUploaderId());
        $this->assertEquals($data['format_note'], $object->getFormatNote());
        $this->assertEquals($data['like_count'], $object->getLikeCount());
        $this->assertEquals($data['duration'], $object->getDuration());
        $this->assertEquals($data['id'], $object->getId());
        $this->assertEquals($data['container'], $object->getContainer());
        $this->assertEquals($data['average_rating'], $object->getAverageRating());
        $this->assertEquals($data['abr'], $object->getAbr());
        $this->assertEquals($data['fps'], $object->getFps());
        $this->assertEquals($data['webpage_url_basename'], $object->getWebpageUrlBasename());
        $this->assertEquals($data['asr'], $object->getAsr());
        $this->assertEquals($data['description'], $object->getDescription());
        $this->assertEquals($data['filesize'], $object->getFilesize());
        $this->assertEquals($data['format_id'], $object->getFormatId());
        $this->assertEquals($data['url'], $object->getUrl());
        $this->assertEquals($data['title'], $object->getTitle());
        $this->assertEquals($data['stitle'], $object->getStitle());
        $this->assertEquals($data['ext'], $object->getExt());
        $this->assertEquals($data['extractor_key'], $object->getExtractorKey());
        $this->assertEquals($data['vcodec'], $object->getVcodec());
        $this->assertEquals($data['webpage_url'], $object->getWebpageUrl());

        $this->assertInstanceOf('SimpleXMLElement', $object->getAnnotations());

        $this->assertInstanceOf('YoutubeDl\Entity\Subtitles', $object->getSubtitles()[0]);
        $this->assertEquals('en', $object->getSubtitles()[0]->getLocale());
        $this->assertInstanceOf('YoutubeDl\Entity\Caption', $object->getSubtitles()[0]->getCaptions()[1]);
        $this->assertEquals(2, $object->getSubtitles()[0]->getCaptions()[1]->getIndex());
        $this->assertEquals('00:00:07.300000', $object->getSubtitles()[0]->getCaptions()[1]->getStart()->format('H:i:s.u'));
        $this->assertEquals('00:00:09.000000', $object->getSubtitles()[0]->getCaptions()[1]->getEnd()->format('H:i:s.u'));
        $this->assertEquals('But usually it is only one of the components that causes the problem.', $object->getSubtitles()[0]->getCaptions()[1]->getCaption());

        $this->assertInstanceOf('YoutubeDl\Entity\Category', $object->getCategories()[0]);
        $this->assertEquals($data['categories'][0], $object->getCategories()[0]->getTitle());

        $this->assertInstanceOf('YoutubeDl\Entity\Thumbnail', $object->getThumbnails()[0]);
        $this->assertEquals($data['thumbnails'][0]['id'], $object->getThumbnails()[0]->getId());
        $this->assertEquals($data['thumbnails'][0]['url'], $object->getThumbnails()[0]->getUrl());

        $this->assertInstanceOf('YoutubeDl\Entity\Format', $object->getFormats()[0]);
        $this->assertEquals($data['formats'][0]['format'], $object->getFormats()[0]->getFormat());
        $this->assertEquals($data['formats'][0]['url'], $object->getFormats()[0]->getUrl());
        $this->assertEquals($data['formats'][0]['vcodec'], $object->getFormats()[0]->getVcodec());
        $this->assertEquals($data['formats'][0]['format_note'], $object->getFormats()[0]->getFormatNote());
        $this->assertEquals($data['formats'][0]['abr'], $object->getFormats()[0]->getAbr());
        $this->assertEquals($data['formats'][0]['player_url'], $object->getFormats()[0]->getPlayerUrl());
        $this->assertEquals($data['formats'][0]['ext'], $object->getFormats()[0]->getExt());
        $this->assertEquals($data['formats'][0]['preference'], $object->getFormats()[0]->getPreference());
        $this->assertEquals($data['formats'][0]['format_id'], $object->getFormats()[0]->getFormatId());
        $this->assertEquals($data['formats'][0]['acodec'], $object->getFormats()[0]->getAcodec());
        $this->assertEquals($data['formats'][0]['container'], $object->getFormats()[0]->getContainer());
        $this->assertEquals($data['formats'][0]['width'], $object->getFormats()[0]->getWidth());
        $this->assertEquals($data['formats'][0]['height'], $object->getFormats()[0]->getHeight());
        $this->assertEquals($data['formats'][0]['asr'], $object->getFormats()[0]->getAsr());
        $this->assertEquals($data['formats'][0]['tbr'], $object->getFormats()[0]->getTbr());
        $this->assertEquals($data['formats'][0]['fps'], $object->getFormats()[0]->getFps());
        $this->assertEquals($data['formats'][0]['filesize'], $object->getFormats()[0]->getFilesize());
    }
}
