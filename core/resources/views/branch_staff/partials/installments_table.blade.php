<div class="card b-radius--10">
    <div class="card-body p-0">
        <div class="table-responsive--md table-responsive">
            <table class="table table--light style--two">

                <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>@lang('Installment Date')</th>
                        <th>@lang('Given On')</th>
                        <th>@lang('Delay')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investment->installments as $installment)
                        <tr>
                            <td>{{ __($loop->index + 1) }}</td>

                            <td class="{{ !$installment->given_date && $installment->installment_date < today() ? 'text--danger' : '' }}">
                                {{ showDateTime($installment->installment_date, 'd M, Y') }}
                            </td>

                            <td>
                                @if ($installment->given_date)
                                    {{ showDateTime($installment->given_date, 'd M, Y') }}
                                @else
                                    @lang('Not yet')
                                @endif
                            </td>
                            <td>
                                @if ($installment->given_date)
                                    {{ $installment->given_date->diffInDays($installment->installment_date) }} @lang('Day')
                                @else
                                    ...
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('style')
    <style>
        .list-group {
            gap: 1rem;
        }

        .list-group-item {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            border: 0;
            padding: 0;
        }

        .caption {
            font-size: 0.8rem;
            color: #b7b7b7;
        }

        .value {
            font-size: 1rem;
            color: #787d85;
            font-weight: 500;
        }
    </style>
@endpush
