<?php
namespace sarelvdwalt\ResponseJSONEnvelope;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;

class ResponseJSON extends Response
{
    /**
     * @var array
     */
    private $with = array();

    /**
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * @param array $with
     * @return $this
     */
    public function setWith($with)
    {
        $this->with = $with;

        return $this->build();
    }

    /**
     * Constructor.
     *
     * @param mixed $content The response content, see setContent()
     * @param int $status The response status code
     * @param array $headers An array of response headers
     *
     * @param array $extra_meta
     * @api
     */
    public function __construct($content = '', $status = self::HTTP_OK, $headers = array(), $extra_meta = array())
    {
        parent::__construct('', self::HTTP_OK, array('Content-Type' => 'application/json'));

        $this->_content = $content;
        $this->_status = $status;
        $this->_extra_meta = $extra_meta;

        return $this->build();
    }

    /**
     * Builds the custom ResponseJSON Object and return it with the relevant extra info needed
     *
     * @return $this
     * @internal param $content
     * @internal param $status
     * @internal param $extra_meta
     */
    protected function build()
    {
        $now = new \DateTime('now');

        $meta = array_merge(
            array(
                'status' => $this->_status,
                'timestamp' => $now->format('U')
            ),
            $this->_extra_meta
        );

        if (count($this->getWith()) > 0) {
            $meta = array_merge($meta, array('with' => $this->getWith()));
        }

        $serializerContext = SerializationContext::create();
        if (count($this->getWith()) <= 0) {
            $serializerContext->setGroups(array('basic'));
        } else {
            $serializerContext->setGroups(array_merge(array('basic'), $this->getWith()));
        }

        $serializer = SerializerBuilder::create()->build();
        $json = $serializer->serialize(
            array(
                'content' => $this->_content,
                'meta' => $meta
            ), 'json', $serializerContext);

        $this->setContent($json);
        $this->setStatusCode($this->_status);

        return $this;
    }
}
