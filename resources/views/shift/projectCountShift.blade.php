<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件カウント') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="button-under">
            <a href="{{route('shift.')}}" class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">トップに戻る</a>
            <a href="{{route('shift.employeeShowShift')}}" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員閲覧用シフト</a>
            <a href="{{route('shift.projectPriceShift')}}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">上代閲覧用シフト</a>
            <a href="{{route('shift.employeePriceShift')}}" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員給与シフト</a>
        </div>

        <div class="caleder">
            <form action="{{route('shift.projectCountSelectWeek')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$startOfWeek}}">
                <input type="hidden" name="action" value="previous">
                <button type="submit" class="prev-week action-weekBtn">
                    前の週
                </button>
            </form>
            <form action="{{route('shift.projectCountSelectWeek')}}" class="calender-view" method="POST">
                @csrf
                <input type="date" id="date" name="date">
                <button type="submit" class="calender-view__button">
                    検索
                </button>
            </form>
            <form action="{{route('shift.projectCountSelectWeek')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$endOfWeek}}">
                <input type="hidden" name="action" value="next">
                <button type="submit" class="prev-week action-weekBtn">
                    次の週
                </button>
            </form>
        </div>

        <?php $projectShowCheck = true;?>
        <?php $unProjectShowCheck = true;?>
        <div class="x-scroll">
            @if (!$shiftDataByDay->isEmpty())
                <div class="shift-count">
                    @foreach ($shiftDataByDay as $date => $shiftData)
                        <div class="shift-count__day">
                            <p>{{$date}}</p>
                            <div class="shift-count__day__project">
                                @foreach ($projects as $project)
                                    <div class="shift-count__project">
                                        <?php $projectCount = 0;?>
                                        @if ($projectShowCheck)
                                            <p class="shift-count__projectName">{{$project->name}}</p>
                                        @endif
                                        @foreach ($shiftData as $shift)
                                            @foreach ($shift->projectsVehicles as $spv)
                                                @if($spv->project)
                                                    @if ($project->id == $spv->project_id)
                                                        <?php $projectCount++?>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <p>{{$projectCount}}</p>
                                    </div>
                                @endforeach
                                @foreach ($unregistered_project as $project)
                                    <div class="shift-count__project">
                                        <?php $unProjectCount = 0;?>
                                        @if ($unProjectShowCheck)
                                            <p class="shift-count__projectName" style="color:red;">{{$project}}</p>
                                        @endif
                                        @foreach ($shiftData as $shift)
                                            @foreach ($shift->projectsVehicles as $spv)
                                                @if(!$spv->project)
                                                    @if ($project == $spv->unregistered_project)
                                                        <?php $unProjectCount++?>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <p>{{$unProjectCount}}</p>
                                    </div>
                                @endforeach
                                <?php $projectShowCheck = false;?>
                                <?php $unProjectShowCheck = false;?>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>{{$startOfWeek}}〜{{$endOfWeek}}のシフトはありません</p>
            @endif
        </div>
    </div>
</x-app-layout>
