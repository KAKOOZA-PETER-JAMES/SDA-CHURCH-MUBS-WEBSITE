<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Smalot\PdfParser\Parser;

class ImportChurchBoardPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'churchboard:import-pdf {pdf=Church.-Board-QSG.pdf}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Church Board departments and role summaries from PDF';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pdfPath = base_path($this->argument('pdf'));

        if (! File::exists($pdfPath)) {
            $this->error('PDF file not found: '.$pdfPath);
            return 1;
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        $normalized = str_replace(["\r\n", "\r"], "\n", $text);
        $normalized = preg_replace('/[ \t]+/', ' ', $normalized);
        $normalized = preg_replace('/\n{2,}/', "\n\n", $normalized);

        $departments = [
            'Pastor(s)',
            'Elder(s)',
            'Head deacon',
            'Head deaconess',
            'Treasurer',
            'Clerk',
            'Personal ministries leader',
            'Personal ministries secretary',
            'Men’s ministries coordinator',
            'Publishing ministries coordinator',
            'Bible school coordinator',
            'Community services leader',
            'Sabbath school superintendent',
            'Family ministries leader',
            'Women’s ministries leader',
            'Children’s ministries coordinator',
            'Education secretary',
            'Home and School Association leader',
            'Adventist Youth Society leader',
            'Pathfinder Club director',
            'Adventurer Club director',
            'Interest coordinator',
            'Communication committee chairperson or communication secretary',
            'Stewardship leader',
            'Religious liberty leader',
        ];

        $documentResponsibilities = $this->extractDocumentResponsibilities($normalized);

        $roleMap = [];
        foreach ($departments as $department) {
            $roleMap[$department] = [
                'head' => 'Department Head',
                'positionTitle' => $department,
                'roles' => $documentResponsibilities,
            ];
        }

        $outputDir = storage_path('app/church-board');
        if (! File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        File::put($outputDir.'/raw_text.txt', $normalized);
        File::put(
            $outputDir.'/roles.json',
            json_encode($roleMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->info('Imported PDF and generated:');
        $this->line(' - storage/app/church-board/raw_text.txt');
        $this->line(' - storage/app/church-board/roles.json');

        return 0;
    }

    private function extractDocumentResponsibilities(string $text): array
    {
        $roles = [];

        if (preg_match('/Here is a short list of what will be included:(.+?)(MINIMUM REQUIREMENTS OF\s+A BOARD MEMBER|Role of the Church Board Member)/su', $text, $match)) {
            preg_match_all('/•\s*(.+)/u', $match[1], $bulletMatches);
            foreach ($bulletMatches[1] as $bullet) {
                $clean = trim($bullet);
                if ($clean !== '' && mb_strlen($clean) > 10) {
                    $roles[] = $clean;
                }
            }
        }

        if (empty($roles) && preg_match('/Board Member\s+Job Description(.+?)Responsibilities of a Church Board/su', $text, $match)) {
            $summary = trim(preg_replace('/\s+/u', ' ', $match[1]));
            if ($summary !== '') {
                $roles[] = 'Together with other board members, is legally and morally responsible for congregation activities.';
                $roles[] = 'Nurtures and promotes the vision and mission of the congregation/district.';
                $roles[] = 'Supports determination of congregational policy, annual budget, and church goals.';
            }
        }

        if (empty($roles)) {
            $roles = [
                'Setting policy for the church.',
                'Establishing and supporting the church mission and purpose.',
                'Serving as fiscal agent and managing resources responsibly.',
                'Conducting planning, evaluations, and program oversight.',
            ];
        }

        return array_values(array_unique($roles));
    }
}
