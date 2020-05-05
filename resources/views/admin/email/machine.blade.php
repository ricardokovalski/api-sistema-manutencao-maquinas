@extends('admin.email.layout.master')

@section('content')
    <div style="padding:20px">
        <p>Prezado, responsável técnico {{ $technical }} a máquina {{ $machine->name }} necessecita de manutenção.</p>

        <h3 style="text-decoration: underline">Dados da Máquina</h3>

        <div style="margin-left: 10px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-weight: 600">Nome</label>
                        <p style="padding-left: 10px">
                            {{ $machine->name }}
                        </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-weight: 600">Descrição</label>
                        <p style="padding-left: 10px">
                            {{ $machine->description }}
                        </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-weight: 600">Informações Técnicas</label>
                        <p style="padding-left: 10px">
                            {{ $machine->technical }}
                        </p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="font-weight: 600">Patrimônio</label>
                        <p style="padding-left: 10px">
                            {{ $machine->patrimony }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection