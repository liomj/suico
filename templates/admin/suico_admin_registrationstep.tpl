<table>
    <th><{$smarty.const._AM_SUICO_STEPNAME}></th>
    <th><{$smarty.const._AM_SUICO_STEPORDER}></th>
    <th><{$smarty.const._AM_SUICO_STEPSAVE}></th>
    <th><{$smarty.const._AM_SUICO_ACTION}></th>
    <{foreach item=step from=$steps}>
        <tr class="<{cycle values='odd, even'}>">
            <td><{$step.step_name}></td>
            <td align="center"><{$step.step_order}></td>
            <td align="center">
                <a href="registrationstep.php?op=toggle&amp;step_save=<{$step.step_save}>&amp;step_id=<{$step.step_id}>"><img
                            src="<{xoModuleIcons16}><{$step.step_save}>.png" title="<{$smarty.const._AM_SUICO_SAVESTEP_TOGGLE}>"
                            alt="<{$smarty.const._AM_SUICO_SAVESTEP_TOGGLE}>"></a>
            </td>
            <td align="center">
                <a href="registrationstep.php?id=<{$step.step_id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 'edit.png'}>"
                                                                                                         alt="<{$smarty.const._EDIT}>"
                                                                                                         title="<{$smarty.const._EDIT}>"></a>
                &nbsp;<a href="registrationstep.php?op=delete&amp;id=<{$step.step_id}>" title="<{$smarty.const._DELETE}>"><img
                            src="<{xoModuleIcons16 'delete.png'}>" alt="<{$smarty.const._DELETE}>" title="<{$smarty.const._DELETE}>"</a>
            </td>
        </tr>
    <{/foreach}>
</table>
