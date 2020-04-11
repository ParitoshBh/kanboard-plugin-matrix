<?php

namespace Kanboard\Plugin\Matrix\Notification;

use Kanboard\Core\Base;
use Kanboard\Core\Notification\NotificationInterface;

/**
 * Matrix Notification
 *
 * @package  notification
 * @author   Paritosh Bhatia
 */
class Matrix extends Base implements NotificationInterface
{
    const NOTIFICATION_TYPE_USER = 1;
    const NOTIFICATION_TYPE_PROJECT = 2;

    /**
     * Send notification to a user
     *
     * @access public
     * @param  array     $user
     * @param  string    $eventName
     * @param  array     $eventData
     */
    public function notifyUser(array $user, $eventName, array $eventData)
    {
        $serverUrl = $this->userMetadataModel->get(
            $user['id'], 
            'matrix_server_url', 
            $this->configModel->get('matrix_server_url')
        );

        $accessToken = $this->userMetadataModel->get(
            $user['id'], 
            'matrix_access_token', 
            $this->configModel->get('matrix_access_token')
        );

        $roomId = $this->userMetadataModel->get(
            $user['id'], 
            'matrix_room_id', 
            $this->configModel->get('matrix_room_id')
        );

        self::__sendMessage($serverUrl, $accessToken, $roomId, $eventName, $eventData, self::NOTIFICATION_TYPE_USER, $user);
    }

    /**
     * Send notification to a project
     *
     * @access public
     * @param  array     $project
     * @param  string    $event_name
     * @param  array     $event_data
     */
    public function notifyProject(array $project, $event_name, array $event_data)
    {
        // die('notifyProject');
        // $webhook = $this->projectMetadataModel->get(
        //     $project['id'], 
        //     'matrix_access_token', 
        //     $this->configModel->get('matrix_access_token')
        // );

        // $channel = $this->projectMetadataModel->get($project['id'], 'matrix_webhook_channel');

        // if (! empty($webhook)) {
        //     $this->sendMessage($webhook, $channel, $project, $event_name, $event_data);
        // }
    }

    /**
     * Get message to send
     *
     * @access public
     * @param  array     $project
     * @param  string    $event_name
     * @param  array     $event_data
     * @return array
     */
    private function __buildPayload($eventName, array $eventData, int $notificationType, array $typeData)
    {
        if ($this->userSession->isLogged()) {
            $title = $this->notificationModel->getTitleWithAuthor(
                $this->helper->user->getFullname(), 
                $eventName, 
                $eventData
            );
        } else {
            $title = $this->notificationModel->getTitleWithoutAuthor($eventName, $eventData);
        }

        switch ($notificationType) {
            case self::NOTIFICATION_TYPE_USER:
                $message .= 'Update for task "' . $eventData['task']['title'] . "\"\n";
                break;
            case self::NOTIFICATION_TYPE_PROJECT:
                // $message = '**['.$project['name']."]** ";
                break;
            default:
                # code...
                break;
        }

        // if ($this->configModel->get('application_url') !== '') {
        // }

        $message .= $title."\n";

        return $message;
    }

    /**
     * Send message to Matrix
     *
     * @access private
     * @param  string    $webhook
     * @param  string    $channel
     * @param  array     $project
     * @param  string    $event_name
     * @param  array     $event_data
     */
    private function __sendMessage($serverUrl, $accessToken, $roomId, $eventName, array $eventData, int $notificationType, array $typeData)
    {
        if (!empty($serverUrl) && !empty($accessToken) && !empty($roomId)) {
            $url = $serverUrl . '/_matrix/client/r0/rooms/' . $roomId . '/send/m.room.message?access_token=' . $accessToken;
            $payload = [
                'msgtype' => 'm.text',
                'body' => self::__buildPayload($eventName, $eventData, $notificationType, $typeData)
            ];

            $this->httpClient->postJsonAsync($url, $payload, [], true);
        }
    }
}