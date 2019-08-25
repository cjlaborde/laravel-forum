@extends ('layouts.app')

@section ('content')

    <div class="container">
{{--        <ais-instant-search>--}}
{{--        <ais-search-box autofocus>--}}
{{--            <input--}}
{{--                slot-scope="{ currentRefinement, refine }"--}}
{{--                :value="currentRefinement"--}}
{{--                @input="refine($event.currentTarget.value)"--}}
{{--                placeholder="Custom SearchBox"--}}
{{--            />--}}
{{--        </ais-search-box>--}}
{{--            </ais-instant-search>--}}
{{--            <ais-instant-search>--}}
{{--        <ais-index--}}
{{--            app-id="latency"--}}
{{--            api-key="3d9875e51fbd20c7754e65422f7ce5e1"--}}
{{--            index-name="bestbuy"--}}
{{--        >--}}
{{--            <ais-search-box></ais-search-box>--}}
{{--            <ais-results>--}}
{{--                <template slot-scope="{ result }">--}}
{{--                    <h2>--}}
{{--                        <ais-highlight :result="result" attribute-name="name"></ais-highlight>--}}
{{--                    </h2>--}}
{{--                </template>--}}
{{--            </ais-results>--}}
{{--        </ais-index>--}}
{{--                </ais-instant-search>--}}
    <media></media>
    </div>

{{--<scan></scan>--}}



@endsection
