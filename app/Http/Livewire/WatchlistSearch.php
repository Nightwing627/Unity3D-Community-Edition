<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Watchlist;
use Livewire\Component;
use Livewire\WithPagination;

class WatchlistSearch extends Component
{
    use WithPagination;

    public $user;

    public $perPage = 25;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    final public function mount(): void
    {
        $this->user = \auth()->user();
    }

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getUsersProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Watchlist::query()
            ->with(['user', 'author'])
            ->when($this->search, fn ($query) => $query->where('message', 'LIKE', '%'.$this->search.'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.watchlist-search', [
            'watchedUsers' => $this->users,
        ]);
    }
}
