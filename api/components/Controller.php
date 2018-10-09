<?php

namespace api\components;

use Yii;
use yii\web\Response;

class Controller extends \yii\rest\Controller
{
    protected $_reqData = array();

    protected $_uid = 0;

    protected $_files_content = array();

    protected $_user = null;

    protected $code = 10000;
    protected $data = [];
    protected $msg = "";

    const REQUEST_SUCCESS = 10000;


    /**
     * function_description
     *
     *
     * @return
     */
    public function init()
    {
        parent::init();

        $this->parseRequest();
    }


    /**
     * parse request post data
     * use boundary --------GOUMIN_UPLOAD_BOUNDARY-------
     *
     * @return
     */
    protected function parseRequest()
    {
        $boundary = "--------SUMMER_UPLOAD_BOUNDARY--------";
        $postData = file_get_contents("php://input");
        $a = explode($boundary, $postData);
        $this->_reqData = @json_decode($a[0], true);
        $this->_files_content = array_slice($a, 1);
    }

    /**
     * function_description
     *
     * @param $buffer :
     *
     * @return image ext name or "" if not image
     */
    public function getImageBufferExt(&$buffer)
    {
        $finfo = new \finfo(FILEINFO_MIME);
        $mime = explode(";", $finfo->buffer($buffer))[0];
        switch ($mime) {
            case 'image/png':
            case 'image/x-png':
                return "png";
            case 'image/pjpeg':
            case 'image/jpeg':
                return "jpg";
            case 'image/gif':
                return "gif";
            default:
                return "";
        }

    }

    /**
     * get request seq number
     *
     *
     * @return
     */
    public function getSeqnum()
    {
        return @$this->_reqData['seqnum'] ?: "";
    }



    /**
     * function_description
     *
     * @param $name :
     *
     * @return
     */
    public function getData($name)
    {
        return @$this->_reqData["data"][$name] ?: "";
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function isLogin()
    {
        return $this->_uid != 0;
    }

    /**
     * return file contents data from request
     *
     *
     * @return array(filedata...)
     */
    public function getFiles()
    {
        return $this->_files_content;
    }


    /**
     * send json encoded response to user
     * @param code : response code
     * @param array data: real response body
     * @param msg : response message, default "".
     *
     * @return string json encoded response string ,with shield "for (;;);{body}end;;;"
     */
    public function sendResponse($code, array $data = [], $msg = "")
    {
        $response = array(
            'code' => intval($code),
            'message' => trim($msg),
            'seqnum' => $this->getSeqnum(),
            'data' => $data,
        );
        $body = @json_encode($response);
        header('Content-Type: text/json; charset=utf-8', true);

        //return "for (;;);" . $body . "end;;;";
        return  $body;
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        if($this->msg == ''){
            $this->msg = Yii::$app->message->showError($this->code);
        }
        echo $this->sendResponse($this->code, $this->data, $this->msg);

        return $result;
    }
}

