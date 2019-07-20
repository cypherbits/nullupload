<?php

namespace Base;

use \Files as ChildFiles;
use \FilesQuery as ChildFilesQuery;
use \Exception;
use \PDO;
use Map\FilesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'files' table.
 *
 *
 *
 * @method     ChildFilesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFilesQuery orderByOrigname($order = Criteria::ASC) Order by the origName column
 * @method     ChildFilesQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method     ChildFilesQuery orderByExtension($order = Criteria::ASC) Order by the extension column
 * @method     ChildFilesQuery orderByUploaddate($order = Criteria::ASC) Order by the uploadDate column
 * @method     ChildFilesQuery orderByNdownloads($order = Criteria::ASC) Order by the nDownloads column
 * @method     ChildFilesQuery orderByLastdownload($order = Criteria::ASC) Order by the lastDownload column
 * @method     ChildFilesQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildFilesQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildFilesQuery orderByDeletedate($order = Criteria::ASC) Order by the deleteDate column
 * @method     ChildFilesQuery orderByDeletepassword($order = Criteria::ASC) Order by the deletePassword column
 * @method     ChildFilesQuery orderByIntegrity($order = Criteria::ASC) Order by the integrity column
 * @method     ChildFilesQuery orderByUser($order = Criteria::ASC) Order by the user column
 *
 * @method     ChildFilesQuery groupById() Group by the id column
 * @method     ChildFilesQuery groupByOrigname() Group by the origName column
 * @method     ChildFilesQuery groupByFilename() Group by the filename column
 * @method     ChildFilesQuery groupByExtension() Group by the extension column
 * @method     ChildFilesQuery groupByUploaddate() Group by the uploadDate column
 * @method     ChildFilesQuery groupByNdownloads() Group by the nDownloads column
 * @method     ChildFilesQuery groupByLastdownload() Group by the lastDownload column
 * @method     ChildFilesQuery groupByType() Group by the type column
 * @method     ChildFilesQuery groupByPassword() Group by the password column
 * @method     ChildFilesQuery groupByDeletedate() Group by the deleteDate column
 * @method     ChildFilesQuery groupByDeletepassword() Group by the deletePassword column
 * @method     ChildFilesQuery groupByIntegrity() Group by the integrity column
 * @method     ChildFilesQuery groupByUser() Group by the user column
 *
 * @method     ChildFilesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFilesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFilesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFilesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFilesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFilesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFiles findOne(ConnectionInterface $con = null) Return the first ChildFiles matching the query
 * @method     ChildFiles findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFiles matching the query, or a new ChildFiles object populated from the query conditions when no match is found
 *
 * @method     ChildFiles findOneById(string $id) Return the first ChildFiles filtered by the id column
 * @method     ChildFiles findOneByOrigname(string $origName) Return the first ChildFiles filtered by the origName column
 * @method     ChildFiles findOneByFilename(string $filename) Return the first ChildFiles filtered by the filename column
 * @method     ChildFiles findOneByExtension(string $extension) Return the first ChildFiles filtered by the extension column
 * @method     ChildFiles findOneByUploaddate(string $uploadDate) Return the first ChildFiles filtered by the uploadDate column
 * @method     ChildFiles findOneByNdownloads(int $nDownloads) Return the first ChildFiles filtered by the nDownloads column
 * @method     ChildFiles findOneByLastdownload(string $lastDownload) Return the first ChildFiles filtered by the lastDownload column
 * @method     ChildFiles findOneByType(string $type) Return the first ChildFiles filtered by the type column
 * @method     ChildFiles findOneByPassword(string $password) Return the first ChildFiles filtered by the password column
 * @method     ChildFiles findOneByDeletedate(string $deleteDate) Return the first ChildFiles filtered by the deleteDate column
 * @method     ChildFiles findOneByDeletepassword(string $deletePassword) Return the first ChildFiles filtered by the deletePassword column
 * @method     ChildFiles findOneByIntegrity(string $integrity) Return the first ChildFiles filtered by the integrity column
 * @method     ChildFiles findOneByUser(int $user) Return the first ChildFiles filtered by the user column *

 * @method     ChildFiles requirePk($key, ConnectionInterface $con = null) Return the ChildFiles by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOne(ConnectionInterface $con = null) Return the first ChildFiles matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFiles requireOneById(string $id) Return the first ChildFiles filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByOrigname(string $origName) Return the first ChildFiles filtered by the origName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByFilename(string $filename) Return the first ChildFiles filtered by the filename column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByExtension(string $extension) Return the first ChildFiles filtered by the extension column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByUploaddate(string $uploadDate) Return the first ChildFiles filtered by the uploadDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByNdownloads(int $nDownloads) Return the first ChildFiles filtered by the nDownloads column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByLastdownload(string $lastDownload) Return the first ChildFiles filtered by the lastDownload column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByType(string $type) Return the first ChildFiles filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByPassword(string $password) Return the first ChildFiles filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByDeletedate(string $deleteDate) Return the first ChildFiles filtered by the deleteDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByDeletepassword(string $deletePassword) Return the first ChildFiles filtered by the deletePassword column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByIntegrity(string $integrity) Return the first ChildFiles filtered by the integrity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFiles requireOneByUser(int $user) Return the first ChildFiles filtered by the user column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFiles[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFiles objects based on current ModelCriteria
 * @method     ChildFiles[]|ObjectCollection findById(string $id) Return ChildFiles objects filtered by the id column
 * @method     ChildFiles[]|ObjectCollection findByOrigname(string $origName) Return ChildFiles objects filtered by the origName column
 * @method     ChildFiles[]|ObjectCollection findByFilename(string $filename) Return ChildFiles objects filtered by the filename column
 * @method     ChildFiles[]|ObjectCollection findByExtension(string $extension) Return ChildFiles objects filtered by the extension column
 * @method     ChildFiles[]|ObjectCollection findByUploaddate(string $uploadDate) Return ChildFiles objects filtered by the uploadDate column
 * @method     ChildFiles[]|ObjectCollection findByNdownloads(int $nDownloads) Return ChildFiles objects filtered by the nDownloads column
 * @method     ChildFiles[]|ObjectCollection findByLastdownload(string $lastDownload) Return ChildFiles objects filtered by the lastDownload column
 * @method     ChildFiles[]|ObjectCollection findByType(string $type) Return ChildFiles objects filtered by the type column
 * @method     ChildFiles[]|ObjectCollection findByPassword(string $password) Return ChildFiles objects filtered by the password column
 * @method     ChildFiles[]|ObjectCollection findByDeletedate(string $deleteDate) Return ChildFiles objects filtered by the deleteDate column
 * @method     ChildFiles[]|ObjectCollection findByDeletepassword(string $deletePassword) Return ChildFiles objects filtered by the deletePassword column
 * @method     ChildFiles[]|ObjectCollection findByIntegrity(string $integrity) Return ChildFiles objects filtered by the integrity column
 * @method     ChildFiles[]|ObjectCollection findByUser(int $user) Return ChildFiles objects filtered by the user column
 * @method     ChildFiles[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FilesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\FilesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Files', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFilesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFilesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFilesQuery) {
            return $criteria;
        }
        $query = new ChildFilesQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFiles|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FilesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FilesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFiles A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, origName, filename, extension, uploadDate, nDownloads, lastDownload, type, password, deleteDate, deletePassword, integrity, user FROM files WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFiles $obj */
            $obj = new ChildFiles();
            $obj->hydrate($row);
            FilesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildFiles|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FilesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FilesTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById('fooValue');   // WHERE id = 'fooValue'
     * $query->filterById('%fooValue%', Criteria::LIKE); // WHERE id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $id The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the origName column
     *
     * Example usage:
     * <code>
     * $query->filterByOrigname('fooValue');   // WHERE origName = 'fooValue'
     * $query->filterByOrigname('%fooValue%', Criteria::LIKE); // WHERE origName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $origname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByOrigname($origname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($origname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_ORIGNAME, $origname, $comparison);
    }

    /**
     * Filter the query on the filename column
     *
     * Example usage:
     * <code>
     * $query->filterByFilename('fooValue');   // WHERE filename = 'fooValue'
     * $query->filterByFilename('%fooValue%', Criteria::LIKE); // WHERE filename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByFilename($filename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_FILENAME, $filename, $comparison);
    }

    /**
     * Filter the query on the extension column
     *
     * Example usage:
     * <code>
     * $query->filterByExtension('fooValue');   // WHERE extension = 'fooValue'
     * $query->filterByExtension('%fooValue%', Criteria::LIKE); // WHERE extension LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extension The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByExtension($extension = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extension)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_EXTENSION, $extension, $comparison);
    }

    /**
     * Filter the query on the uploadDate column
     *
     * Example usage:
     * <code>
     * $query->filterByUploaddate('2011-03-14'); // WHERE uploadDate = '2011-03-14'
     * $query->filterByUploaddate('now'); // WHERE uploadDate = '2011-03-14'
     * $query->filterByUploaddate(array('max' => 'yesterday')); // WHERE uploadDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $uploaddate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByUploaddate($uploaddate = null, $comparison = null)
    {
        if (is_array($uploaddate)) {
            $useMinMax = false;
            if (isset($uploaddate['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_UPLOADDATE, $uploaddate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($uploaddate['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_UPLOADDATE, $uploaddate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_UPLOADDATE, $uploaddate, $comparison);
    }

    /**
     * Filter the query on the nDownloads column
     *
     * Example usage:
     * <code>
     * $query->filterByNdownloads(1234); // WHERE nDownloads = 1234
     * $query->filterByNdownloads(array(12, 34)); // WHERE nDownloads IN (12, 34)
     * $query->filterByNdownloads(array('min' => 12)); // WHERE nDownloads > 12
     * </code>
     *
     * @param     mixed $ndownloads The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByNdownloads($ndownloads = null, $comparison = null)
    {
        if (is_array($ndownloads)) {
            $useMinMax = false;
            if (isset($ndownloads['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_NDOWNLOADS, $ndownloads['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ndownloads['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_NDOWNLOADS, $ndownloads['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_NDOWNLOADS, $ndownloads, $comparison);
    }

    /**
     * Filter the query on the lastDownload column
     *
     * Example usage:
     * <code>
     * $query->filterByLastdownload('2011-03-14'); // WHERE lastDownload = '2011-03-14'
     * $query->filterByLastdownload('now'); // WHERE lastDownload = '2011-03-14'
     * $query->filterByLastdownload(array('max' => 'yesterday')); // WHERE lastDownload > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastdownload The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByLastdownload($lastdownload = null, $comparison = null)
    {
        if (is_array($lastdownload)) {
            $useMinMax = false;
            if (isset($lastdownload['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_LASTDOWNLOAD, $lastdownload['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastdownload['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_LASTDOWNLOAD, $lastdownload['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_LASTDOWNLOAD, $lastdownload, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the deleteDate column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedate('2011-03-14'); // WHERE deleteDate = '2011-03-14'
     * $query->filterByDeletedate('now'); // WHERE deleteDate = '2011-03-14'
     * $query->filterByDeletedate(array('max' => 'yesterday')); // WHERE deleteDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByDeletedate($deletedate = null, $comparison = null)
    {
        if (is_array($deletedate)) {
            $useMinMax = false;
            if (isset($deletedate['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_DELETEDATE, $deletedate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedate['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_DELETEDATE, $deletedate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_DELETEDATE, $deletedate, $comparison);
    }

    /**
     * Filter the query on the deletePassword column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletepassword('fooValue');   // WHERE deletePassword = 'fooValue'
     * $query->filterByDeletepassword('%fooValue%', Criteria::LIKE); // WHERE deletePassword LIKE '%fooValue%'
     * </code>
     *
     * @param     string $deletepassword The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByDeletepassword($deletepassword = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($deletepassword)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_DELETEPASSWORD, $deletepassword, $comparison);
    }

    /**
     * Filter the query on the integrity column
     *
     * Example usage:
     * <code>
     * $query->filterByIntegrity('fooValue');   // WHERE integrity = 'fooValue'
     * $query->filterByIntegrity('%fooValue%', Criteria::LIKE); // WHERE integrity LIKE '%fooValue%'
     * </code>
     *
     * @param     string $integrity The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByIntegrity($integrity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($integrity)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_INTEGRITY, $integrity, $comparison);
    }

    /**
     * Filter the query on the user column
     *
     * Example usage:
     * <code>
     * $query->filterByUser(1234); // WHERE user = 1234
     * $query->filterByUser(array(12, 34)); // WHERE user IN (12, 34)
     * $query->filterByUser(array('min' => 12)); // WHERE user > 12
     * </code>
     *
     * @param     mixed $user The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function filterByUser($user = null, $comparison = null)
    {
        if (is_array($user)) {
            $useMinMax = false;
            if (isset($user['min'])) {
                $this->addUsingAlias(FilesTableMap::COL_USER, $user['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($user['max'])) {
                $this->addUsingAlias(FilesTableMap::COL_USER, $user['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilesTableMap::COL_USER, $user, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFiles $files Object to remove from the list of results
     *
     * @return $this|ChildFilesQuery The current query, for fluid interface
     */
    public function prune($files = null)
    {
        if ($files) {
            $this->addUsingAlias(FilesTableMap::COL_ID, $files->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the files table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FilesTableMap::clearInstancePool();
            FilesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FilesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FilesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FilesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FilesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FilesQuery
