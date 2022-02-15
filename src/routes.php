<?php

extract($_GET);

if (!isset($section) || $section == 'home'):
	include 'main/main-index.php';
elseif ($section == 'program' and $_login):
	// programacion
	if ($sbs == 'listpeople'):
		include 'program/list-people.php';
	elseif ($sbs == 'createprogram'):
		include 'program/create-program.php';
	elseif ($sbs == 'manageprogram'):
		include 'program/manage-program.php';
	elseif ($sbs == 'editprogram'):
		include 'program/edit-program.php';
	elseif ($sbs == 'approveprogram'):
		include 'program/approve-program.php';
	// diagnostico
	elseif ($sbs == 'creatediagno'):
		include 'program/create-diagnosis.php';
	// personal
	elseif ($sbs == 'createpersonal'):
		include 'program/create-people.php';
	elseif ($sbs == 'managepersonal'):
		include 'program/manage-people.php';
	elseif ($sbs == 'editpeople'):
		include 'program/edit-people.php';
	// other
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'agenda' and $_login):
	// agenda
	if ($sbs == 'createagenda'):
		include 'agenda/list-people.php';
	elseif ($sbs == 'setagenda'):
		include 'agenda/create-agenda.php';
	elseif ($sbs == 'createblock'):
		include 'agenda/create-block.php';
	elseif ($sbs == 'manageagendas'):
		include 'agenda/manage-agenda.php';
	elseif ($sbs == 'modifyagenda'):
		include 'agenda/modify-agenda.php';
	elseif ($sbs == 'agendasperson'):
		include 'agenda/agenda-person.php';
	elseif ($sbs == 'viewagenda'):
		include 'agenda/view-agenda.php';
	elseif ($sbs == 'viewagendapeople'):
		include 'agenda/view-agenda-people.php';
	elseif ($sbs == 'manageblocks'):
		include 'agenda/manage-blocks.php';
	// other
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'box' and $_login):
	// boxes
	if ($sbs == 'createoccupation'):
		include 'box/create-occupation.php';
	elseif ($sbs == 'manageoccupation'):
		include 'box/manage-occupation.php';
	elseif ($sbs == 'occupationbox'):
		include 'box/occupation-box.php';
	elseif ($sbs == 'occupationfloor'):
		include 'box/occupation-floor.php';
	// other
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'reports' and $_login):
	// programado por especialidad
	if ($sbs == 'viewprogramesp'):
		include 'reports/view-program-by-special.php';
	// programacion
	elseif ($sbs == 'viewprogram'):
		include 'reports/view-program.php';
	// rendimiento
	elseif ($sbs == 'viewperform'):
		include 'reports/view-perform.php';
	// medicos no programados
	elseif ($sbs == 'viewmedics'):
		include 'reports/view-medics.php';
	// medicos justificados
	elseif ($sbs == 'viewjustify'):
		include 'reports/view-justify.php';
	// reprogramaciones
	elseif ($sbs == 'viewreprogram'):
		include 'reports/view-reprogram.php';
	// reprogramaciones
	elseif ($sbs == 'viewperc'):
		include 'reports/view-perc.php';
	// other
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'contact' and $_login):
	if ($sbs == 'sentmessages'):
		include 'contactability/manage-sent.php';
	elseif ($sbs == 'receivedmessages'):
		include 'contactability/manage-received.php';
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'adminusers' and $_login):
	// dashboard
	if ($sbs == 'editprofile'):
		include 'admin/users/edit-profile.php';
	// carmbiar pass
	elseif ($sbs == 'changepass'):
		include 'admin/users/change-password.php';
	// other
	else:
		include 'src/error.php';
	endif;
// users
elseif ($section == 'users' and $_admin):
	if ($sbs == 'createuser'):
		include 'admin/users/create-user.php';
	elseif ($sbs == 'manageusers'):
		include 'admin/users/manage-users.php';
	elseif ($sbs == 'edituser'):
		include 'admin/users/edit-user.php';
	else:
		include 'src/error.php';
	endif;
// groups
elseif ($section == 'groups' and $_admin):
	if ($sbs == 'creategroup'):
		include 'admin/groups/create-group.php';
	elseif ($sbs == 'managegroups'):
		include 'admin/groups/manage-groups.php';
	elseif ($sbs == 'editgroup'):
		include 'admin/groups/edit-group.php';
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'production' and $_admin):
	// files
	if ($sbs == 'loadfile'):
		include 'admin/files/load-file.php';
	else:
		include 'src/error.php';
	endif;
elseif ($section == 'forgotpass'):
	include 'admin/users/retrieve-password.php';
else:
	include 'src/error.php';
endif;