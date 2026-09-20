<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Graph\GraphExporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function __construct(private readonly GraphExporter $exporter) {}

    public function show(Request $request): JsonResponse
    {
        $snapshot = $this->exporter->snapshot();

        return $this->cached(response()->json($snapshot));
    }

    public function node(string $slug): JsonResponse
    {
        $snapshot = $this->exporter->snapshot();
        $node = $snapshot['nodes'][$slug] ?? null;
        abort_unless($node, 404);

        $edges = array_intersect_key($snapshot['edges'], array_flip($node['edges']));
        // "connected" also carries the structural neighbours the page needs to label: parent, children,
        // the body's positions, and the body a position heads.
        $ids = array_unique(array_filter(array_merge(
            $node['connectedNodes'],
            $node['children'] ?? [],
            $node['positions'] ?? [],
            [$node['parent'] ?? null, $node['head'] ?? null, $node['headOf'] ?? null],
        )));
        $neighbours = array_intersect_key($snapshot['nodes'], array_flip($ids));

        return $this->cached(response()->json([
            'node' => $node,
            'edges' => array_values($edges),
            'connected' => array_values(array_map(fn ($n) => [
                'id' => $n['id'], 'type' => $n['type'], 'subtype' => $n['subtype'] ?? null, 'sector' => $n['sector'], 'name' => $n['name'],
            ], $neighbours)),
        ]));
    }

    public function layout(): JsonResponse
    {
        return $this->cached(response()->json($this->exporter->layout()));
    }

    public function version(): JsonResponse
    {
        return response()->json(['version' => $this->exporter->version()])->header('Cache-Control', 'no-store');
    }

    private function cached(JsonResponse $response): JsonResponse
    {
        return $response
            ->header('Cache-Control', 'public, max-age=60, s-maxage=86400, stale-while-revalidate=600')
            ->header('X-Graph-Version', $this->exporter->version());
    }
}
