<h3 class="sect-title"><span class="iico iico36 iico-root"></span> Reportes <small> :: Ver Personal no Planificado</small></h3>

<ol class="breadcrumb">
    <li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active">Ver Personal no Planificado</li>
</ol>

<h4 class="sect-subtitle">Personal Registrado</h4>

<form role="form" id="formViewMedics">
    <div class="row">
        <div class="form-group col-xs-3 has-feedback" id="gdate">
            <label class="control-label" for="idate">Mes de Planiificación</label>
            <div class="input-group input-group-sm">
                <i class="input-group-addon fa fa-calendar"></i>
                <input type="text" class="form-control" id="iNdate" name="idate" data-date-format="mm/yyyy" placeholder="MM/AAAA" required>
            </div>
            <i class="fa form-control-feedback" id="icondate"></i>
        </div>
        
        <div class="form-group col-xs-4 has-feedback" id="gplanta">
            <label class="control-label" for="iplanta">Planta *</label>
            <select class="form-control input-sm" id="iNplanta" name="iplanta" required>
                <option value="">Seleccione una planta</option>
                <option value="0">MÉDICA</option>
                <option value="1">NO MÉDICA</option>
                <option value="2">ODONTOLÓGICA</option>
            </select>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-xs-12">
            <button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i>Buscar</button>
            <span class="ajaxLoader" id="submitLoader"></span>
        </div>
    </div>
</form>

<hr>

<table id="tpeople" class="hover row-border" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>RUT</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Profesión</th>
        </tr>
    </thead>

    <tbody>
    </tbody>
</table>

<script src="reports/view-medics.js"></script>