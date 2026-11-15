<?php

namespace App\Http\Controllers;

use App\Models\Norm;
use App\Models\Service;
use App\Models\Training;
use App\Support\Catalog;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $services = Service::query()->published()->ordered()->get();
        $trainings = Training::query()->published()->ordered()->get();

        // Alle kommenden Termine aller Schulungen, chronologisch
        $upcoming = $trainings
            ->flatMap(fn (Training $t) => $t->upcomingDates()->map(fn ($d) => $d + ['training' => $t]))
            ->sortBy(fn ($d) => $d['start']->timestamp)
            ->take(4)
            ->values();

        return view('pages.home', [
            'services' => $services,
            'servicesByCategory' => $services->groupBy('category'),
            'tests' => $services->whereIn('icon', ['sine', 'random', 'shock'])->keyBy('icon'),
            'resonance' => $services->firstWhere('demo', 'resonance'),
            'upcoming' => $upcoming,
            'norms' => Norm::query()->published()->ordered()->limit(12)->get(),
            'normCount' => Norm::query()->published()->count(),
        ]);
    }

    public function services(): View
    {
        $services = Service::query()->published()->ordered()->get();

        return view('services.index', [
            'servicesByCategory' => $services->groupBy('category'),
        ]);
    }

    public function service(Service $service): View
    {
        abort_unless($service->is_published, 404);

        return view('services.show', [
            'service' => $service,
            'related' => Service::query()->published()->ordered()
                ->where('id', '!=', $service->id)
                ->where('category', $service->category)
                ->limit(3)->get(),
        ]);
    }

    public function trainings(): View
    {
        return view('trainings.index', [
            'trainings' => Training::query()->published()->ordered()->get(),
        ]);
    }

    public function training(Training $training): View
    {
        abort_unless($training->is_published, 404);

        return view('trainings.show', ['training' => $training]);
    }

    public function norms(): View
    {
        $norms = Norm::query()->published()->ordered()->get();
        $used = $norms->pluck('category')->unique();

        return view('norms.index', [
            'norms' => $norms,
            'categories' => collect(Catalog::NORM_CATEGORIES)->only($used->all()),
        ]);
    }

    public function norm(Norm $norm): View
    {
        abort_unless($norm->is_published, 404);

        return view('norms.show', [
            'norm' => $norm,
            'related' => Norm::query()->published()->ordered()
                ->where('id', '!=', $norm->id)
                ->where('category', $norm->category)
                ->limit(6)->get(),
        ]);
    }

    public function calculators(): View
    {
        return view('pages.calculators');
    }

    public function imprint(): View
    {
        return view('pages.legal', ['type' => 'imprint']);
    }

    public function privacy(): View
    {
        return view('pages.legal', ['type' => 'privacy']);
    }

    public function sitemap(): Response
    {
        $urls = collect([
            route('home'), route('services.index'), route('trainings.index'),
            route('norms.index'), route('calculators'), route('contact'),
        ])
            ->merge(Service::query()->published()->pluck('slug')->map(fn ($s) => route('services.show', $s)))
            ->merge(Training::query()->published()->pluck('slug')->map(fn ($s) => route('trainings.show', $s)))
            ->merge(Norm::query()->published()->pluck('slug')->map(fn ($s) => route('norms.show', $s)));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls->map(fn ($u) => '  <url><loc>'.e($u).'</loc></url>')->implode("\n")
            ."\n</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
