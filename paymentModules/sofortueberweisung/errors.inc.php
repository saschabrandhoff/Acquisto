<?php
/// \cond

/*
 * defines the different error-codes, the pnag-API can provide
 * depending which faulure occurs, the consumer can correct the error (e.g. bad telephone number) and continue with his order OR use another payment method
 * @internal
 */


define('CONSUMER_FAULTS', 	'8019,8020,8023');

/*
//currently is every NOT-CONSUMER-FAULT a SHOP-FAULT
define('SHOP_FAULTS', 		'7000,7001,7002,7004,7005,7006,7007,7008,7009,7010,7011,7012,7013,7014,
							 8000,8001,8002,8003,8004,8005,8006,8010,8011,8012,8013,8014,8015,8016,8017,8018,
							 8021,8022,8024,8025,8026,8027,8028,8029,8030,8031,8032,8033,8034,8035,8036,8037,8038,8039,
							 8040,8041,8042,8043,8044,8045,8046,8047,8048,8049,8050,8051,8052,8053,8054,8055,8056,8057,
							 9000,9001,9002,9003,9004,9005,9006,9007,9008,9009,9010,9011,9012');
*/
/// \endcond