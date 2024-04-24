<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --client-white-board">
            <div class="btn-area">
                <div class="c-save-btn project-info-save-btn" id="saveBtn">
                    登録
                </div>
                <a href="{{ route('project.info', ['id' => $project->id]) }}" class="c-back-btn">
                    戻る
                </a>
            </div>
            <div class="project-info-box">
                <table class="project-info-box__top-table">
                    <tr>
                        <td class="project-head blue-bg">案件名</td>
                        <td class="project-name">{{ $project->name }}</td>
                        <td class="client-head blue-bg">クライアント名</td>
                        <td class="client-name">{{ $project->client->name }}</td>
                    </tr>
                </table>
                <form action="{{ route('project.infoUpdate') }}" method="POST" id="form">
                    @csrf
                    <input hidden type="text" name="project_id" value="{{ $project->id }}">
                    <table class="project-info-box__middle-table">
                        <tr>
                            <td class="head blue-bg">配達種類</td>
                            <td class="dd"><input type="text" name="delivery_type" value="{{ $project->projectDetail ? $project->projectDetail->delivery_type : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">着車場所</td>
                            <td class="dd"><input type="text" name="arrival_location" value="{{ $project->projectDetail ? $project->projectDetail->arrival_location : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">配達エリア</td>
                            <td class="dd"><input type="text" name="delivery_area" value="{{ $project->projectDetail ? $project->projectDetail->delivery_area : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">納品先</td>
                            <td class="dd"><input type="text" name="delivery_address" value="{{ $project->projectDetail ? $project->projectDetail->delivery_address : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">荷物の種類</td>
                            <td class="dd"><input type="text" name="cargo_type" value="{{ $project->projectDetail ? $project->projectDetail->cargo_type : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">着車時間</td>
                            <td class="dd"><input type="text" name="arrival_time" value="{{ $project->projectDetail ? $project->projectDetail->arrival_time : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">終了時間</td>
                            <td class="dd"><input type="text" name="finish_time" value="{{ $project->projectDetail ? $project->projectDetail->finish_time : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">件数</td>
                            <td class="dd"><input type="text" name="count" value="{{ $project->projectDetail ? $project->projectDetail->count : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">稼働日</td>
                            <td class="dd"><input type="text" name="operation_date" value="{{ $project->projectDetail ? $project->projectDetail->operation_date : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">車両</td>
                            <td class="dd"><input type="text" name="vehicle" value="{{ $project->projectDetail ? $project->projectDetail->vehicle : null }}"></td>
                        </tr>
                        <tr>
                            <td class="head blue-bg">代引き</td>
                            <td class="dd"><input type="text" name="cash_on_delivery" value="{{ $project->projectDetail ? $project->projectDetail->cash_on_delivery : null }}"></td>
                        </tr>
                    </table>
                    <table class="project-info-box__amount-table">
                        <colgroup>
                            <col style="width: 10%;">
                            <col style="width: 40%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="blue-bg" colspan="2">(株)T.N.G(税別)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="head">上代</td>
                                <td class="dd"><input type="text" value="{{ $financialMetrics['retail_price'] != 0 ? number_format($financialMetrics['retail_price']) : null }}" readonly id="retailPrice"></td>
                            </tr>
                            <tr>
                                <td class="head">T.N.G Dr</td>
                                <td class="dd"><input type="text" value="{{ $financialMetrics['driver_price'] != 0 ? number_format($financialMetrics['driver_price']) : null }}" readonly></td>
                            </tr>
                            <tr>
                                <td class="head">(株)H.G.L</td>
                                <td class="dd"><input type="text" name="retail_price_for_hgl" value="{{ $financialMetrics['retail_price_for_hgl'] != 0 ? number_format($financialMetrics['retail_price_for_hgl']) : null }}" class="commaInput" id="hglRetail"></td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="head">頭抜き</td>
                                <td>
                                    <div class="table-layout">
                                        <div class="project-table-cell blue-bg" style="width: 20%;">(株)T.N.G Dr</div>
                                        <div class="project-table-cell">{{ number_format($financialMetrics['tng_head']) }}</div>
                                        <div class="project-table-cell blue-bg" style="width: 15%;">利益率</div>
                                        <div class="project-table-cell">{{ $financialMetrics['profit_rate_tng'] != 0 ? round($financialMetrics['profit_rate_tng'] * 100).'%' : null }}</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-layout">
                                        <div class="project-table-cell blue-bg" style="width: 20%;">(株)H.G.L</div>
                                        <div class="project-table-cell"><input type="text" value="{{ $financialMetrics['hgl_head'] != 0 ? number_format($financialMetrics['hgl_head']) : null }}" readonly id="headInput"></div>
                                        <div class="project-table-cell blue-bg" style="width: 15%;">利益率</div>
                                        <div class="project-table-cell"><input type="text" value="{{ $financialMetrics['profit_rate_hgl'] != 0 ? round($financialMetrics['profit_rate_hgl'] * 100).'%' : null }}" readonly id="profitRate"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="remarks-box">
                        <textarea name="notes" id="" cols="30" rows="10">{{ $project->projectDetail ? $project->projectDetail->notes : null }}</textarea>
                    </div>
                </form>
            </div>
        </div>
    </main>

</x-app-layout>
<div class="test"></div>

{{-- script --}}
<script src="{{asset('js/project-info.js')}}"></script>

