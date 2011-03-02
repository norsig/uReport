<?php
/**
 * When running this script, we don't yet have departments loaded.
 * Make sure to comment out the Department required field from
 * User->validate() before running this.
 * You can uncomment the validation immediately once this script is done
 *
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../../../configuration.inc';
include './migrationConfig.inc';

$pdo = new PDO(MIGRATION_DSN,MIGRATION_USER,MIGRATION_PASS);

$result = $pdo->query('select username,authenticationMethod,firstname,lastname from users u,people p where p.id=u.person_id');
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$person = new Person();

	try {
		$user = new User($row['username']);
	}
	catch (Exception $e) {
		$user = new User();
		$user->setUsername($row['username']);
		$user->setAuthenticationMethod($row['authenticationMethod']);
		if($row['authenticationMethod'] == 'LDAP'){
			try {
				$ldap = new LDAPEntry($user->getUsername());
				$person->setFirstname($ldap->getFirstname());
				$person->setLastname($ldap->getLastname());
				$person->setEmail($ldap->getEmail());
				if($ldap->getDepartment()){
				  $department = new Department($ldap->getDepartment());
				  $user->setDepartment($department);
				}
			}
			catch (Exception $e) {
				$person->setEmail($row['username'].'@bloomington.in.gov');
			}
		}
		else{
		  $person->setFirstname($row['firstname']);
		  $person->setLastname($row['lastname']);
		}
		try {
			$person->save();
			$user->setPerson($person);
			$user->save();
		}
		catch (Exception $e) {
			print_r($e);
			print_r($person);
			print_r($user);
			exit();
		}
	}
	echo $user->getUsername()."\n";
}