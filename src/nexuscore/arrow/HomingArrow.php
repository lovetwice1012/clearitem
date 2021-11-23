<?php
namespace nexuscore\arrow;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\{
	Entity,
	Living,
    Location
};
use pocketmine\entity\Human;
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\math\RayTraceResult;

final class HomingArrow extends Arrow{
        protected $gravity = 0;
	protected $damage = 3.0;
	protected $punchKnockback = 2.0;
	private $shooter;
	private $level;

    
	public function __construct(Location $location, ?CompoundTag $nbt = null, ?Entity $entity = null, bool $critical = false , ?World $level = null){
		parent::__construct(
			$location,
			$entity,
			$critical,
			$nbt
		);
		if($entity === null) return;
		//$this->setMotion($entity->getDirectionVector()->normalize()->multiply(0.5));
		$this->shooter = $entity;
		$this->level = $level;
	}

	public function entityBaseTick(int $tick = 1):bool{
 	  $newTarget = $this->level->getNearestEntity($this->getLocation(), 50.0, Living::class);
          if($newTarget instanceof Living){
            if($this->shooter === null){
	      $currentTarget = null;
	    }else{
              if($this->shooter->getId() !== $newTarget->getId()){
	        $currentTarget = $newTarget;
	      }else{
	        $currentTarget = null;
	      }
	    }
	  }else{
            $currentTarget = null;
          }

	  if($currentTarget !== null){
		$vector = $currentTarget->getPosition()->add(0, $currentTarget->getEyeHeight() / 2, 0)->subtract($this->getLocation()->getX(),$this->getLocation()->getY(),$this->getLocation()->getZ())->divide(2.0);

		$distance = $vector->lengthSquared();
		if($distance < 1){
		  $diff = $vector->normalize()->multiply(5 * (1 - sqrt($distance)) ** 2);
		  $this->motion->x += $diff->x;
		  $this->motion->y += $diff->y;
		  $this->motion->z += $diff->z;
		}
	  }
		foreach($this->level->getEntities() as $entity){
			if(
				!$entity instanceof Living
					or
				$this->shooter === null
					or
				$this->shooter->getId() === $entity->getId()
					or
				$this->getLocation()->distance($entity->getLocation()) > 3
					or
				($bb = $entity->getBoundingBox()) === null
			) continue;

			$this->onHitEntity(
				$entity,
				new RayTraceResult(
					$bb,
					1,
					$entity->getLocation()
				)
			);
        break;
		}
		return parent::entityBaseTick($tick);
	}
}
