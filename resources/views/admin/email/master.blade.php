{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
    {{--<base href="/" />--}}
    {{--<title>Novus</title>--}}
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--</head>--}}
{{--<body>--}}
{{--<div style="padding:20px">--}}
    {{--<h3 style="text-decoration: underline">Dados da Máquina</h3>--}}
    {{--<div style="margin-left: 10px;">--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Nome</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.name }}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Descrição</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.description }}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Informações Técnicas</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.technical }}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-2">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Patrimônio</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.patrimony }}</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-2">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Período de Revisão</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.review_period }} dias</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-2">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">Período de Aviso</label>--}}
                    {{--<p style="padding-left: 10px">{{ machine.warning_period }} dias</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-6">--}}
                {{--<div class="form-group">--}}
                    {{--<label style="font-weight: 600">E-mail de Aviso</label>--}}
                    {{--<p style="padding-left: 10px">--}}
                        {{--{{ machine.warning_email_address }}--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div style="padding-left: 10px; margin-top: 40px;">--}}
        {{--<h3 style="text-decoration: underline">Peças de Reposição</h3>--}}
        {{--<div class="card-body">--}}
            {{--<div class="table-responsive">--}}
                {{--<table class="table table-sm">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th class="text-left" scope="row">Nome</th>--}}
                        {{--<th class="text-left" scope="row">Descrição</th>--}}
                        {{--<th class="text-center" scope="row">Quant. Mínima</th>--}}
                        {{--<th class="text-center" scope="row">Em Estoque</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--<tr *ngFor="let piece of machine.pieces">--}}
                        {{--<td class="text-left">{{ piece.name }}</td>--}}
                        {{--<td class="text-left">{{ piece.description }}</td>--}}
                        {{--<td class="text-center">{{ piece.minimal_quantity }}</td>--}}
                        {{--<td class="text-center">{{ piece.stock_quantity }}</td>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
            {{--<div *ngIf="machine.pieces <= 0">--}}
                {{--Não há nenhum registro.--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div style="padding-left: 10px; margin-top: 40px;">--}}
        {{--<h3 style="text-decoration: underline">Responsáveis</h3>--}}
        {{--<div class="card-body">--}}
            {{--<div class="table-responsive">--}}
                {{--<table class="table table-sm">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th class="text-left" scope="row">Nome</th>--}}
                        {{--<th class="text-left" scope="row">E-mail</th>--}}
                        {{--<th class="text-center" scope="row">Telefone</th>--}}
                        {{--<th class="text-center" scope="row">Inf. Adicionais</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--<tr *ngFor="let manager of machine.users">--}}
                        {{--<td class="text-left">{{ manager.name }}</td>--}}
                        {{--<td class="text-left">{{ manager.email }}</td>--}}
                        {{--<td class="text-center">{{ manager.telephone }}</td>--}}
                        {{--<td class="text-center">{{ manager.additional }}</td>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
            {{--<div *ngIf="machine.users <= 0">--}}
                {{--Não há nenhum registro.--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}
