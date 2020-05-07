<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Admin\Notifiers
 */

/**
 * Represents the logic for showing the notification.
 */
class WPSEO_Configuration_Notifier implements WPSEO_Listener {

	/**
	 * Option name used to determine whether the notice has been dismissed.
	 *
	 * @var string
	 */
	const META_NAME = 'wpseo-dismiss-configuration-notice';

	/**
	 * Default value.
	 *
	 * @var string
	 */
	const META_VALUE = 'yes';

	/**
	 * Should the notification be shown.
	 *
	 * @var bool
	 */
	protected $show_notification;

	/**
	 * Constructs the object by setting the show notification property based the given options.
	 */
	public function __construct() {
		$this->show_notification = WPSEO_Options::get( 'show_onboarding_notice', false );
	}

	/**
	 * Returns the content of the notification.
	 *
	 * @return string A string with the notification HTML, or empty string when no notification is needed.
	 */
	public function notify() {

		if ( ! $this->show_notification() ) {
			$this->re_run_notification();
			return;
		}
		if ( WPSEO_Options::get( 'started_configuration_wizard' ) ) {
			$this->continue_notification();
			return;
		}
		$this->first_time_notification();
	}

	/**
	 * Listens to an argument in the request URL. When triggered just sets the notification to dismissed.
	 *
	 * @return void
	 */

	public function listen() {
		if ( ! $this->show_notification() || ! $this->dismissal_is_triggered() ) {
			return;
		}

		$this->set_dismissed();
	}

	/**
	 * Checks if the dismissal should be triggered.
	 *
	 * @return bool True when action has been triggered.
	 */
	protected function dismissal_is_triggered() {
		return filter_input( INPUT_GET, 'dismiss_get_started' ) === '1';
	}

	/**
	 * Checks if the current user has dismissed the notification.
	 *
	 * @return bool True when the notification has been dismissed.
	 */
	protected function is_dismissed() {
		return get_user_meta( get_current_user_id(), self::META_NAME, true ) === self::META_VALUE;
	}

	/**
	 * Sets the dismissed state for the current user.
	 *
	 * @return void
	 */
	protected function set_dismissed() {
		update_user_meta( get_current_user_id(), self::META_NAME, self::META_VALUE );
	}

	/**
	 * Checks if the notification should be shown.
	 *
	 * @return bool True when notification should be shown.
	 */
	protected function show_notification() {
		return $this->show_notification && ! $this->is_dismissed();
	}

	/**
	 * Adds the re-run notification to the notification centre
	 *
	 * @return void
	 */
	public function re_run_notification() {
		$note         = new Wizard_Notification();
		$message      = $note->get_notification_message( 'finish' );
		$notification = new Yoast_Notification(
			$message,
			[
				'type'         => Yoast_Notification::WARNING,
				'id'           => 'wpseo-dismiss-onboarding-notice',
				'capabilities' => 'wpseo_manage_options',
				'priority'     => 0.1,
			]
		);

		$notification_center = Yoast_Notification_Center::get();
		$notification_center->add_notification( $notification );

	}

	/**
	 * Adds the first-time notification to the notification centre
	 *
	 * @return void
	 */
	public function first_time_notification() {
		$note         = new Wizard_Notification();
		$message      = $note->get_notification_message( 'start' );
		$notification = new Yoast_Notification(
			$message
			,
			[
				'type'         => Yoast_Notification::WARNING,
				'id'           => 'wpseo-dismiss-onboarding-notice',
				'capabilities' => 'wpseo_manage_options',
				'priority'     => 1,
			]
		);

		$notification_center = Yoast_Notification_Center::get();
		$notification_center->add_notification( $notification );

	}

	/**
	 * Adds the continue notification to the notification centre
	 *
	 * @return void
	 */
	public function continue_notification() {
		$note         = new Wizard_Notification();
		$message      = $note->get_notification_message( 'continue' );
		$notification = new Yoast_Notification(
			$message,
			[
				'type'         => Yoast_Notification::WARNING,
				'id'           => 'wpseo-dismiss-onboarding-notice',
				'capabilities' => 'wpseo_manage_options',
				'priority'     => 1,
			]
		);

		$notification_center = Yoast_Notification_Center::get();
		$notification_center->add_notification( $notification );

	}
}
